<?php

declare(strict_types=1);

namespace In2code\In2glossar\Hooks;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMText;
use Exception;
use In2code\In2glossar\Domain\Model\Definition;
use In2code\In2glossar\Utility\DatabaseUtility;
use In2code\In2glossar\Utility\EnvironmentUtility;
use LogicException;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class ContentPostProcessor implements SingletonInterface
{
    protected ?TypoScriptFrontendController $tsfe = null;
    protected string $excludeClassGeneral = 'in2glossar-excluded';
    /**
     * Check that this script was not already rendered before
     */
    protected bool $done = false;

    public function render(array $reference, TypoScriptFrontendController $tsfe): void
    {
        unset($reference);
        if ($this->done === false) {
            $this->tsfe = $tsfe;

            if ($this->isDefaultTypeNum()) {
                try {
                    $body = $this->getBody();
                    $body = $this->replaceInTags($body);
                    $body = $this->replaceEscaptedTags($body);
                    $this->setBody($body);
                    $this->done = true;
                } catch (Exception $exception) {
                    // todo write to log
                }
            }
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
        $queryBuilder = DatabaseUtility::getQueryBuilderForTable(Definition::TABLE_NAME);
        $results = $queryBuilder
            ->select('uid', 'word', 'synonyms', 'short_description')
            ->from(Definition::TABLE_NAME)
            ->where('tooltip', 1)
            ->execute()
            ->fetchAll();
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
        $body = preg_replace('~\[(\/?)span([^\]]*)\]~', '<$1span$2>', $body);
        return $body;
    }

    protected function domTextReplace(string $search, string $label, string $title, int $uid, DOMNode $domNode): void
    {
        if ($domNode->hasChildNodes()) {
            if ($this->isAllowedByGeneralClassName($domNode)) {
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
    }

    protected function wrapReplace(string $replace, string $title, int $uid): string
    {
        return '[abbr class="in2glossar-abbr" data-in2glossar-title="' . $title
            . '" data-in2glossar-url="' . $this->getTarget($uid) . '"]$1[span]'
            . $replace . '[/span][/abbr]';
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
            if (in_array($parent->tagName, EnvironmentUtility::getExcludedTagNames()) === true) {
                return false;
            }
            foreach (EnvironmentUtility::getExcludedClassNames() as $className) {
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
        if (!empty($result[1])) {
            return $result[1];
        }
        throw new LogicException('No body tag found', 1612449248);
    }

    /**
     * Check if any parent element owns excludeClassGeneral
     */
    protected function isAllowedByGeneralClassName(DOMNode $node): bool
    {
        if (method_exists($node, 'hasAttribute')) {
            if ($node->hasAttribute('class')
                && stristr($node->getAttribute('class'), $this->excludeClassGeneral) !== false) {
                return false;
            }
        }
        return true;
    }

    protected function isDefaultTypeNum(): bool
    {
        return EnvironmentUtility::isDefaultTypeNum();
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
        $settings = EnvironmentUtility::getTyposcriptFrontendController()
            ->tmpl->setup['plugin.']['tx_in2glossar.']['settings.'];
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
