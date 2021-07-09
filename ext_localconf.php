<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {

        /**
         * Register Icons
         */
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
            \TYPO3\CMS\Core\Imaging\IconRegistry::class
        );
        $iconRegistry->registerIcon(
            'extension-in2glossar-definition',
            \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
            ['source' => 'EXT:in2glossar/Resources/Public/Icons/definition.svg']
        );

        /**
         * Include Frontend Plugins
         */
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'In2glossar',
            'Main',
            [
                \In2code\In2glossar\Controller\StandardController::class => 'list',
            ],
            []
        );

        /**
         * Add tooltips to rendered frontend
         */
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][]
            = \In2code\In2glossar\Hooks\ContentPostProcessor::class . '->render';
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][]
            = \In2code\In2glossar\Hooks\ContentPostProcessor::class . '->render';
    }
);
