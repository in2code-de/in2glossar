<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model\Syntax;

use DOMDocument;
use DOMNode;
use In2code\In2glossar\Domain\Model\Replacement;

class Modern implements Syntax
{
    public function create(DOMDocument $document, string $original, Replacement $replacement): DOMNode
    {
        $node = $document->createElement('abbr', $original);
        $node->setAttribute('title', $replacement->shortDescription);
        $node->setAttribute('data-in2glossar-url', $replacement->link);
        return $node;
    }
}
