<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model\Syntax;

use DOMDocument;
use DOMNode;
use In2code\In2glossar\Domain\Model\Replacement;

class Legacy implements Syntax
{
    public function create(DOMDocument $document, string $original, Replacement $replacement): DOMNode
    {
        $node = $document->createElement('abbr', $original);
        $node->setAttribute('class', 'in2glossar-abbr');
        $node->setAttribute('data-in2glossar-title', $replacement->word);
        $node->setAttribute('data-in2glossar-url', $replacement->link);
        $spanChild = $document->createElement('span', $replacement->shortDescription);
        $node->appendChild($spanChild);
        return $node;
    }
}
