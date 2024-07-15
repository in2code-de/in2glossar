<?php
declare(strict_types=1);
namespace In2code\In2glossar\ViewHelpers;

use In2code\In2glossar\Domain\Model\Definition;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * IndexViewHelper
 */
class DefinitionAnchorViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('definition', Definition::class, 'The definition object');
    }

    /**
     * @return string
     */
    public function render()
    {
        $definition = $this->arguments['definition'];
        if ($definition === null) {
            $definition = $this->renderChildren();
            if ($definition === null) {
                return '';
            }
        }
        return implode('-', array(
            'in2glossar',
            'definition',
            $definition->getUid()
        ));
    }
}
