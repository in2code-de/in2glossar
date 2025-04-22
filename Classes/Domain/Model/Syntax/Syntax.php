<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model\Syntax;

use DOMDocument;
use DOMNode;
use In2code\In2glossar\Domain\Model\Replacement;

interface Syntax
{
    public function create(DOMDocument $document, string $original, Replacement $replacement): DOMNode;
}
