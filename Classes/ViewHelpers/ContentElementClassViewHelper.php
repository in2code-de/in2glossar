<?php

declare(strict_types=1);

namespace In2code\In2glossar\ViewHelpers;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ContentElementClassViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('data', 'array', 'The data array of the content element', true);
    }

    public function render(): string
    {
        return self::renderStatic(
            $this->arguments,
            $this->buildRenderChildrenClosure(),
            $this->renderingContext,
        );
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $data = $arguments['data'];
        if ($data === null) {
            $data = $renderChildrenClosure();
            if ($data === null) {
                return '';
            }
        }

        if ($data['tx_in2glossar_exclude'] == 1) {
            return 'in2glossar-excluded';
        }

        return '';
    }
}
