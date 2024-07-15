<?php
declare(strict_types=1);
namespace In2code\In2glossar\ViewHelpers\Be;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * LinkViewHelper
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
        $uri = GeneralUtility::makeInstance(UriBuilder::class)->buildUriFromRoute(
            'record_edit',
            [
                'id' => $id,
                'edit' => [
                    'tx_in2glossar_domain_model_definition' => [
                        $id => 'new',
                    ],
                ],
                'returnUrl' => $this->buildReturnUrl(),
            ]
        );

        $this->tag->addAttribute('href', '#');
        $this->tag->addAttribute(
            'onclick',
            'top.list_frame.location.href=' . GeneralUtility::quoteJSvalue($uri) . '; return false;'
        );

        $this->tag->setContent($this->renderChildren());
        return $this->tag->render();
    }

    /**
     * @return string
     */
    public function buildReturnUrl()
    {
        return $this->renderingContext->getControllerContext()->getUriBuilder()->reset()->build();
    }
}
