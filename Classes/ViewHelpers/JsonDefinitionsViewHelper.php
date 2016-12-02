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
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3Fluid\Fluid\ViewHelpers\RenderViewHelper;

/**
 * JsonDefinitionsViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class JsonDefinitionsViewHelper extends RenderViewHelper
{
    /**
     * @param QueryResultInterface|ObjectStorage|array $definitions
     * @return string|null json value
     */
    public function render($definitions = null)
    {
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
