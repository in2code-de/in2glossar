<?php
namespace In2code\In2glossar\ViewHelpers;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Dominique Kreemers <dominique.kreemers@in2code.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use In2code\In2glossar\Domain\Model\Definition;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * JsonDefinitionsViewHelper
 */
class JsonDefinitionsViewHelper extends AbstractViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('definitions', 'array', 'The iteratable object containing defintions', true);
    }

    /**
     * @return string|null json value
     */
    public function render()
    {
        $definitions = $this->arguments['definitions'];
        if ($definitions === null) {
            $definitions = $this->renderChildren();
            if ($definitions === null) {
                return null;
            }
        }

        $result = array();
        foreach ($definitions as $definition) {
            /* @var $definition Definition */
            $arguments = $this->loadSettingsIntoArguments(array(
                'definition' => $definition,
            ));
            $result[] = array(
                'term' => $definition->getWord(),
                'termregex' => $this->getTermRegEx($definition),
                'description' => $this->viewHelperVariableContainer->getView()->renderPartial('ShortDescription', null, $arguments)
            );
        }
        return json_encode($result);
    }

    /**
     * @param array $arguments
     * @return array
     */
    protected function loadSettingsIntoArguments(array $arguments = array())
    {
        if (!isset($arguments['settings']) && $this->templateVariableContainer->exists('settings')) {
            $arguments['settings'] = $this->templateVariableContainer->get('settings');
        }
        return $arguments;
    }

    /**
     * @param Definition $definition
     * @return string
     */
    protected function getTermRegEx(Definition $definition)
    {
        $terms = array($definition->getWord());
        if (strlen($definition->getSynonyms()) > 0) {
            $terms = array_merge(
                $terms,
                GeneralUtility::trimExplode(',', $definition->getSynonyms())
            );
        }
        return implode('|', $terms);
    }
}
