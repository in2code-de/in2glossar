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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('collection', 'array', 'The iteratable object containing defintions', true);
    }

    /**
     * @return string
     */
    public function render()
    {
        $this->buildIndex($this->arguments['collection']);
        $this->templateVariableContainer->add('index', $this->index);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove('index');
        return $output;
    }

    /**
     * @param array $collection
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
