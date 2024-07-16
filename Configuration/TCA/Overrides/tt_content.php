<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionManagementUtility::addTCAcolumns(
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
    ],
);
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    'tx_in2glossar_exclude',
    '',
    'after:hidden',
);
ExtensionManagementUtility::addStaticFile(
    'in2glossar',
    'Configuration/TypoScript',
    'In2glossar: Main Template',
);
ExtensionUtility::registerPlugin(
    'In2glossar',
    'Main',
    'In2glossar: List- & Definition-View',
);
