<?php
defined('TYPO3_MODE') or die('Access denied.');

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'In2code.' . $_EXTKEY,
        'web',      // Main area
        'mod1',     // Name of the module
        '',         // Position of the module
        array(      // Allowed controller action combinations
            'Backend' => 'index',
        ),
        array(      // Additional configuration
            'access'    => 'user,group',
            'icon'      => 'EXT:' . $_EXTKEY . '/ext_icon.svg',
            'labels'    => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf',
        )
    );
}
