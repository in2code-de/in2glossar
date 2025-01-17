<?php

declare(strict_types=1);

namespace In2code\In2glossar\EventListener;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use Exception;
use In2code\In2glossar\Domain\Model\Definition;
use LogicException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Event\AfterCacheableContentIsGeneratedEvent;

use function array_merge;
use function in_array;
use function is_a;
use function method_exists;
use function preg_match;
use function preg_replace;
use function sprintf;
use function str_replace;
use function stristr;

use const LIBXML_HTML_NODEFDTD;
use const LIBXML_HTML_NOIMPLIED;
use const XML_TEXT_NODE;

class ContentPostProcessor implements SingletonInterface
{
    protected const EXCLUDED_CLASS = 'in2glossar-excluded';

    protected ?TypoScriptFrontendController $tsfe = null;

    /**
     * Check that this script was not already rendered before
     */
    protected array $excludedTagNames;

    protected array $excludedClassNames;

    protected bool $modernMarkup;

    public function __construct(ExtensionConfiguration $extensionConfiguration)
    {
        $config = $extensionConfiguration->get('in2glossar');
        $this->excludedTagNames = GeneralUtility::trimExplode(',', (string) $config['excludedTagNames'], true);
        $this->excludedClassNames = GeneralUtility::trimExplode(',', (string) $config['excludedClassNames'], true);
        $this->modernMarkup = (bool) $config['modernMarkup'];
    }

    public function render(AfterCacheableContentIsGeneratedEvent $event): void
    {
        $this->tsfe = $event->getController();
        if (0 !== (int) $this->tsfe->getPageArguments()->getPageType()) {
            return;
        }

        try {
            $body = $this->getBody();
            $body = $this->replaceInTags($body);
            $body = $this->replaceEscaptedTags($body);
            $this->setBody($body);
        } catch (Exception) {
            // todo write to log
        }
    }

    protected function replaceInTags(string $body): string
    {
        $dom = new DOMDocument();
        @$dom->loadHTML(
            $this->wrapHtmlWithMainTags($body),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );
        foreach ($this->getReplacements() as $set) {
            foreach ((array) $set['searches'] as $search) {
                $this->domTextReplace($search, $set['label'], $set['title'], (int) $set['uid'], $dom);
            }
        }

        return $this->stripMainTagsFromHtml($dom->saveHTML());
    }

    /**
     * Example return values:
     *
     *  [
     *      'uid' => 123,
     *      'title' => 'foo',
     *      'searches' => [
     *          'foo',
     *          'bar',
     *          'technic'
     *      ],
     *      'label' => 'This is the name of a development team of in2code'
     *  ],
     *  [
     *      'uid' => 234,
     *      'title' => 'Anyword',
     *      'searches' => [
     *          'Anyword'
     *      ],
     *      'label' => 'Here is the explanation'
     *  ]
     *
     * @return array<array{'uid': int, 'title': string, 'searches': array<string>, 'label': string}>
     */
    protected function getReplacements(): array
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable(Definition::TABLE_NAME);
        $results = $queryBuilder
            ->select('uid', 'word', 'synonyms', 'short_description')
            ->from(Definition::TABLE_NAME)
            ->where('tooltip', 1)
            ->executeQuery()
            ->fetchAllAssociative();
        $replacements = [];
        foreach ($results as $result) {
            $searches = array_merge([$result['word']], GeneralUtility::trimExplode(',', $result['synonyms'], true));
            $replacements[] = [
                'uid' => (int) $result['uid'],
                'title' => $result['word'],
                'searches' => $searches,
                'label' => $result['short_description'],
            ];
        }

        return $replacements;
    }

    /**
     * Change "[abbr]" to "<abbr>" + "[span]" to "<span>"
     */
    protected function replaceEscaptedTags(string $body): string
    {
        $body = preg_replace('~\[(\/?)abbr([^\]]*)\]~', '<$1abbr$2>', $body);
        return preg_replace('~\[(\/?)span([^\]]*)\]~', '<$1span$2>', (string) $body);
    }

    protected function domTextReplace(string $search, string $label, string $title, int $uid, DOMNode $domNode): void
    {
        if ($domNode->hasChildNodes() && $this->isAllowedByGeneralClassName($domNode)) {
            $children = [];
            foreach ($domNode->childNodes as $child) {
                $children[] = $child;
            }

            /** @var DOMText $child */
            foreach ($children as $child) {
                if ($child->nodeType === XML_TEXT_NODE && $this->isDomElementIncluded($child)) {
                    if (stristr($child->wholeText, $search)) {
                        $newText = preg_replace(
                            '~\b(' . $search . ')\b~Ui',
                            $this->wrapReplace($label, $title, $uid),
                            $child->wholeText,
                        );
                        $newTextNode = $domNode->ownerDocument->createTextNode($newText);
                        $domNode->replaceChild($newTextNode, $child);
                    }
                } else {
                    $this->domTextReplace($search, $label, $title, $uid, $child);
                }
            }
        }
    }

    protected function wrapReplace(string $replace, string $title, int $uid): string
    {
        if ($this->modernMarkup) {
            return sprintf(
                '[abbr title="%s" class="in2glossar-abbr" data-in2glossar-url="%s"]$1[/abbr]',
                $replace,
                $this->getTarget($uid),
            );
        }

        return sprintf(
            '[abbr class="in2glossar-abbr" data-in2glossar-title="%s" data-in2glossar-url="%s"]$1[span]%s[/span][/abbr]',
            $title,
            $this->getTarget($uid),
            $replace,
        );
    }

    /**
     * Check if dom element is allowed:
     * - Is not in excluded tags
     * - Is not in excluded classes
     */
    protected function isDomElementIncluded(DOMNode $element): bool
    {
        $parent = $element->parentNode;
        if (is_a($parent, DOMElement::class)) {
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

        return false;
    }

    protected function setBody(string $body): void
    {
        $this->tsfe->content = preg_replace(
            '/(<body[^>]*>)(.*)(<\/body>)/Uims',
            '$1' . $body . '$3',
            $this->tsfe->content,
        );
    }

    protected function getBody(): string
    {
        preg_match('~<body[^>]*>(.*)<\/body>~Uims', $this->tsfe->content, $result);
        if (isset($result[1]) && ($result[1] !== '' && $result[1] !== '0')) {
            return $result[1];
        }

        throw new LogicException('No body tag found', 1612449248);
    }

    /**
     * Check if any parent element owns excludeClassGeneral
     */
    protected function isAllowedByGeneralClassName(DOMNode $node): bool
    {
        if (!method_exists($node, 'hasAttribute')) {
            return true;
        }
        if ($node->hasAttribute('class')
            && stristr($node->getAttribute('class'), self::EXCLUDED_CLASS) !== false) {
            return false;
        }
        return true;
    }

    /**
     * Wrap html with "<?xml encoding="utf-8" ?><html><body>|</body></html>"
     *
     *  This is a workarround for HTML parsing and wrting with \DOMDocument()
     *      - The html and body tag are preventing strange p-tags while using LIBXML_HTML_NOIMPLIED
     *      - The doctype declaration allows us the usage of umlauts and special characters
     */
    protected function wrapHtmlWithMainTags(string $html): string
    {
        return '<?xml encoding="utf-8" ?><html><body>' . $html . '</body></html>';
    }

    /**
     * Remove tags <?xml encoding="utf-8" ?><html><body></body></html>
     * This function is normally used after wrapHtmlWithMainTags
     */
    protected function stripMainTagsFromHtml(string $html): string
    {
        return str_replace(['<html>', '</html>', '<body>', '</body>', '<?xml encoding="utf-8" ?>'], '', $html);
    }

    protected function getTarget(int $uid): string
    {
        $settings = $this->tsfe->tmpl->setup['plugin.']['tx_in2glossar.']['settings.'];
        if (empty($settings['targetPage'])) {
            throw new LogicException('No target page defined in TypoScript', 1612530083);
        }

        $configuration = [
            'parameter' => (int) $settings['targetPage'],
            'section' => 'in2glossar-definition-' . $uid,
        ];
        /** @var ContentObjectRenderer $contentObject */
        $contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        return $contentObject->typoLink_URL($configuration);
    }
}
