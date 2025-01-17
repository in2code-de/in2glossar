<?php

declare(strict_types=1);

namespace In2code\In2glossar\ViewHelpers;

use In2code\In2glossar\Domain\Model\Definition;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class DefinitionAnchorViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('definition', Definition::class, 'The definition object');
    }

    public function render(): string
    {
        $definition = $this->arguments['definition'];
        if ($definition === null) {
            $definition = $this->renderChildren();
            if ($definition === null) {
                return '';
            }
        }

        return implode('-', [
            'in2glossar',
            'definition',
            $definition->getUid(),
        ]);
    }
}
