<?php
defined('TYPO3_MODE') or die('Access denied.');

/**
 * Include Frontend Plugins
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'In2code.' . $_EXTKEY,
    'Main',
    array(
        'Standard' => 'list, show',
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
