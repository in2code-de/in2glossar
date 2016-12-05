<?php
namespace In2code\In2glossar\ViewHelpers\Be;

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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * LinkViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class LinkViewHelper extends AbstractTagBasedViewHelper
{
    /**
     * @var string
     */
    protected $tagName = 'a';

    /**
     * Arguments initialization
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();
    }

    /**
     * @return string
     */
    public function render()
    {
        $settings = $this->templateVariableContainer->get('settings');
        $id = $settings['storagePid'];
        $uri = BackendUtility::getModuleUrl('record_edit', [
            'id' => $id,
            'edit' => [
                'tx_in2glossar_domain_model_definition' => [
                    $id => 'new',
                ],
            ],
            'returnUrl' => $this->buildReturnUrl(),
        ]);

        $this->tag->addAttribute('href', '#');
        $this->tag->addAttribute('onclick', 'top.list_frame.location.href=' . GeneralUtility::quoteJSvalue($uri) . '; return false;');

        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();

    }

    /**
     * @return string
     */
    public function buildReturnUrl()
    {
        $returnUrl = $this->controllerContext->getUriBuilder()->reset()->build();
        return $returnUrl;
    }
}
