<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model;

use DOMText;
use In2code\In2glossar\Domain\Model\Syntax\Syntax;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use function array_merge;
use function explode;
use function implode;
use function preg_match;

class Replacement
{
    public readonly string $regex;
    public readonly array $synonyms;

    public function __construct(
        public readonly string $word,
        public string $synonymsString,
        public readonly string $link,
        public readonly Syntax $syntax,
        public readonly string $shortDescription,
    ) {
        $this->synonyms = GeneralUtility::trimExplode(',', $this->synonymsString, true);
        $this->regex = '/(?P<match>\b(' . implode('|', array_merge([$this->word], $this->synonyms)) . ')\b)/Ui';
    }

    public function getReplacements(DOMText $domText): ?array
    {
        $matches = [];
        if (1 !== preg_match($this->regex, $domText->wholeText, $matches)) {
            return null;
        }
        [$before, $after] = explode($matches['match'], $domText->wholeText, 2);
        $middle = $this->syntax->create($domText->ownerDocument, $matches['match'], $this);
        return [
            new DOMText($before),
            $middle,
            new DOMText($after),
        ];
    }
}
