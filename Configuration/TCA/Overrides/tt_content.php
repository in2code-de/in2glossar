<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    array(
        'tx_in2glossar_exclude' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tt_content.tx_in2glossar_exclude',
            'config' => array(
                'type' => 'check',
            ),
        ),
    )
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette(
    'tt_content',
    'visibility',
    'tx_in2glossar_exclude'
);
