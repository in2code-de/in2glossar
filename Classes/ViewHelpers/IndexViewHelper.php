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
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3Fluid\Fluid\ViewHelpers\RenderViewHelper;

/**
 * IndexViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class IndexViewHelper extends RenderViewHelper
{
    /**
     * @var array
     */
    protected $index = array();

    /**
     * @return void
     */
    public function initializeObject()
    {
        foreach (range('A', 'Z') as $char) {
            $this->index[$char] = array();
        }
    }

    /**
     * @param QueryResultInterface|ObjectStorage|array $collection
     * @return string
     */
    public function render($collection = null)
    {
        $this->buildIndex($collection);
        $output = '';
        $this->templateVariableContainer->add('index', $this->index);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove('index');
        return $output;

//        foreach ($index as $char => $items) {
//            $this->templateVariableContainer->add('char', $char);
//            $this->templateVariableContainer->add('items', $items);
//            $output.= $this->renderChildren();
//            $this->templateVariableContainer->remove('char');
//            $this->templateVariableContainer->remove('items');
//        }
//        return $output;
    }

    /**
     *
     */
    protected function buildIndex($collection)
    {
        foreach ($collection as $item) {
            /* @var $item AbstractEntity */
            $firstChar = strtoupper(substr($item->_getProperty('word'), 0, 1));
            $this->index[$firstChar][] = $item;
        }
    }
}
