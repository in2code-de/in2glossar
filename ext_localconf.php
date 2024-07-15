<?php

use In2code\In2glossar\Controller\StandardController;
use In2code\In2glossar\Hooks\ContentPostProcessor;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/**
 * Register Icons
 */
$iconRegistry = GeneralUtility::makeInstance(
    IconRegistry::class,
);
$iconRegistry->registerIcon(
    'extension-in2glossar-definition',
    SvgIconProvider::class,
    ['source' => 'EXT:in2glossar/Resources/Public/Icons/definition.svg'],
);

/**
 * Include Frontend Plugins
 */
ExtensionUtility::configurePlugin(
    'In2glossar',
    'Main',
    [
        StandardController::class => 'list',
    ],
);

/**
 * Add tooltips to rendered frontend
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = ContentPostProcessor::class . '->render';
