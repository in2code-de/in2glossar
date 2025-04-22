<?php

declare(strict_types=1);

namespace In2code\In2glossar\EventListener;

use Doctrine\DBAL\ArrayParameterType;
use DOMElement;
use DOMNode;
use DOMText;
use DOMXPath;
use Exception;
use In2code\In2glossar\Domain\Model\Definition;
use In2code\In2glossar\Domain\Model\Replacement;
use In2code\In2glossar\Domain\Model\Syntax\Legacy;
use In2code\In2glossar\Domain\Model\Syntax\Modern;
use IvoPetkov\HTML5DOMDocument;
use Psr\Log\LoggerInterface;
use Throwable;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\TypoScript\FrontendTypoScript;
use TYPO3\CMS\Core\Utility\GeneralUtility as GU;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

use function array_shift;
use function in_array;
use function iterator_to_array;
use function method_exists;
use function str_contains;
use function strtolower;

use const LIBXML_HTML_NODEFDTD;
use const LIBXML_HTML_NOIMPLIED;

class ContentPostProcessor
{
    protected const EXCLUDED_CLASS = 'in2glossar-excluded';
    protected readonly LoggerInterface $logger;
    protected readonly array $excludedTagNames;
    protected readonly array $excludedClassNames;
    protected readonly array $excludedDataAttributes;
    protected readonly bool $modernMarkup;
    protected readonly int $language;

    public function __construct(ExtensionConfiguration $extensionConfiguration, Context $context)
    {
        $this->logger = GU::makeInstance(LogManager::class)->getLogger(static::class);
        $this->language = $context->getPropertyFromAspect('language', 'id');
        $config = $extensionConfiguration->get('in2glossar');
        $this->excludedTagNames = GU::trimExplode(',', (string) $config['excludedTagNames'], true);
        $this->excludedClassNames = GU::trimExplode(',', (string) $config['excludedClassNames'], true);
        $this->excludedDataAttributes = GU::trimExplode(',', (string) $config['excludedDataAttributes'], true);
        $this->modernMarkup = (bool) $config['modernMarkup'];
    }

    /**
     * @throws Throwable
     */
    public function render(AfterCacheableContentIsGeneratedEvent $event): void
    {
        try {
            $this->process($event);
        } catch (Throwable $exception) {
            $this->logger->error('Error while processing glossary', ['exception' => $exception]);
            if (Environment::getContext()->isDevelopment()) {
                throw $exception;
            }
            return;
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws Exception
     */
    protected function process(AfterCacheableContentIsGeneratedEvent $event): void
    {
        if (!$this->shouldProcessPageType($event)) {
            return;
        }
        $targetPage = $this->getTargetPageUid($event);
        $replacements = $this->getReplacements($targetPage);

        $dom = new HTML5DOMDocument();
        $tsfe = $event->getController();
        $body = $tsfe->content;

        $dom->loadHTML($body, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $domXpath = new DOMXPath($dom);
        $bodyElement = $domXpath->query('/html/body')[0] ?? null;
        if (null === $bodyElement) {
            throw new Exception('Content does not have XPath /html/body');
        }

        $this->domTextReplace($bodyElement, $replacements);

        $body = $dom->saveHTML();
        $tsfe->content = $body;
    }

    protected function shouldProcessPageType(AfterCacheableContentIsGeneratedEvent $event): bool
    {
        /** @var PageArguments $pageArguments */
        $pageArguments = $event->getRequest()->getAttribute('routing');
        return 0 === (int) $pageArguments->getPageType();
    }

    /**
     * @throws Exception
     */
    protected function getTargetPageUid(AfterCacheableContentIsGeneratedEvent $event): int
    {
        /** @var FrontendTypoScript $frontendTypoScript */
        $frontendTypoScript = $event->getRequest()->getAttribute('frontend.typoscript');
        $settings = $frontendTypoScript->getSetupArray()['plugin.']['tx_in2glossar.']['settings.'];
        if (empty($settings['targetPage'])) {
            $this->logger->error('No target page defined in TypoScript');
            throw new Exception('No target page defined in TypoScript', 1744892996);
        }
        return (int) $settings['targetPage'];
    }

    /**
     * @return array<Replacement>
     * @throws \Doctrine\DBAL\Exception
     */
    protected function getReplacements(int $targetPage): array
    {
        $connectionPool = GU::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable(Definition::TABLE_NAME);
        $results = $queryBuilder
            ->select('uid', 'word', 'synonyms', 'short_description')
            ->from(Definition::TABLE_NAME)
            ->where(
                $queryBuilder->expr()->eq('tooltip', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT)),
                $queryBuilder->expr()->in(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter([-1, $this->language], ArrayParameterType::INTEGER),
                ),
            )
            ->executeQuery()
            ->fetchAllAssociative();

        $contentObject = GU::makeInstance(ContentObjectRenderer::class);
        $replacements = [];
        $syntax = $this->modernMarkup ? new Modern() : new Legacy();
        foreach ($results as $result) {
            $uid = (int) $result['uid'];
            $link = $contentObject->typoLink_URL([
                'parameter' => $targetPage,
                'section' => 'in2glossar-definition-' . $uid,
            ]);
            $replacements[] = new Replacement(
                $result['word'],
                $result['synonyms'],
                $link,
                $syntax,
                $result['short_description'],
            );
        }
        return $replacements;
    }

    /**
     * @param array<Replacement> $replacements
     */
    protected function domTextReplace(DOMNode $domNode, array $replacements): void
    {
        if (!$this->shouldProcessNode($domNode)) {
            return;
        }

        /** @var array<DOMNode> $children */
        $children = iterator_to_array($domNode->childNodes);

        while ($child = array_shift($children)) {
            if ($child instanceof DOMText) {
                if ($this->isDomElementIncluded($child)) {
                    foreach ($replacements as $replacement) {
                        $childReplacements = $replacement->getReplacements($child);
                        if (null !== $childReplacements) {
                            $parentNode = $child->parentNode;
                            foreach ($childReplacements as $childReplacement) {
                                $parentNode->insertBefore($childReplacement, $child);
                                // we processed and replaced the child for one search word,
                                // but we need to process it for the other search words, too.
                                // Since $child will be deleted (after this loop) from the DOM, we have to process the replacement DOMNodes.
                                // And also recurse them, which is what we are "scheduling" by appending them to the children array.
                                if (!($childReplacement instanceof DOMElement && $childReplacement->tagName === 'abbr')) {
                                    $children[] = $childReplacement;
                                }
                            }
                            $parentNode->removeChild($child);
                            // break to skip processing other replacements with the deleted child
                            break;
                        }
                    }
                }
            } else {
                $this->domTextReplace($child, $replacements);
            }
        }
    }

    /**
     * Check if dom element is allowed:
     * - Is not in excluded tags
     * - Is not in excluded classes
     */
    protected function isDomElementIncluded(DOMNode $element): bool
    {
        $parent = $element->parentNode;

        if (!$parent instanceof DOMElement) {
            return false;
        }

        if (in_array($parent->tagName, $this->excludedTagNames)) {
            return false;
        }

        foreach ($this->excludedClassNames as $className) {
            if ($parent->hasAttribute($className)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if any parent element owns excludeClassGeneral
     */
    protected function shouldProcessNode(DOMNode $node): bool
    {
        if (!$node->hasChildNodes()) {
            return false;
        }

        if (!method_exists($node, 'hasAttribute')) {
            return true;
        }

        if ($node->hasAttribute('class')) {
            $class = strtolower($node->getAttribute('class'));
            if (str_contains($class, self::EXCLUDED_CLASS)) {
                return false;
            }
        }

        foreach ($this->excludedDataAttributes as $attribute) {
            if ($node->hasAttribute($attribute)) {
                return false;
            }
        }

        return true;
    }
}
