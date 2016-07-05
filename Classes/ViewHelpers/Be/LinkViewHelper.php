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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * JsonDefinitionsViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class LinkViewHelper extends AbstractViewHelper
{
    /**
     * @param string $parameters
     * @param string $returnUrl
     * @return string
     */
    public function render($parameters, $returnUrl = '')
    {
        $uri = 'alt_doc.php?' . $parameters;
        if (empty($returnUrl)) {
            $returnUrl = $this->buildReturnUrl();
        }
        $uri .= '&returnUrl=' . rawurlencode($returnUrl);
        return $uri;
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
