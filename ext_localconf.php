<?php
defined('TYPO3_MODE') or die('Access denied.');

/**
 * Register icons
 */
\In2code\In2template\Utility\IconUtility::registerSvgIcons([
    'extension-in2glossar-definition' => 'EXT:in2glossar/Resources/Public/Icons/definition.svg',
]);

/**
 * Include Frontend Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.' . $_EXTKEY,
    'Main',
    array(
        'Standard' => 'list',
    ),
    array()
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.' . $_EXTKEY,
    'Data',
    array(
        'Standard' => 'index',
    ),
    array()
);
