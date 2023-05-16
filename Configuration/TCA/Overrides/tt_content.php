<?php

defined('TYPO3_MODE') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
    'tt_content',
    [
        'tx_in2glossar_exclude' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tt_content.tx_in2glossar_exclude',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tt_content.tx_in2glossar_exclude.I.0',
                    ],
                ],
            ],
        ],
    ]
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'tx_in2glossar_exclude',
    '',
    'after:hidden'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'in2glossar',
    'Configuration/TypoScript',
    'In2glossar: Main Template'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'In2code.in2glossar',
    'Main',
    'In2glossar: List- & Definition-View'
);
