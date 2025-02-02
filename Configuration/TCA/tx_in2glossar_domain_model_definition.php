<?php

declare(strict_types=1);

return [
    'ctrl' => [
        'title' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition',
        'label' => 'word',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'default_sortby' => 'word',
        'dividers2tabs' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'typeicon_classes' => [
            'default' => 'extension-in2glossar-definition',
        ],
        'searchFields' => 'word,short_description,description',
    ],
    'types' => [
        '1' => [
            'showitem' => '
                    sys_language_uid,
                    l10n_parent,
                    l10n_diffsource,
                    hidden,
                    tooltip,
                    word,
                    synonyms,
                    short_description,
                    description,
                --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access,
                    starttime,
                    endtime
                ',
        ],
    ],
    'palettes' => [],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.default_value', 0],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_in2glossar_domain_model_definition',
                'foreign_table_where' => 'AND tx_in2glossar_domain_model_definition.pid=###CURRENT_PID### AND tx_in2glossar_domain_model_definition.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int) date('m'), (int) date('d'), (int) date('Y')),
                ],
                'renderType' => 'inputDateTime',
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, (int) date('m'), (int) date('d'), (int) date('Y')),
                ],
                'renderType' => 'inputDateTime',
            ],
        ],
        'tooltip' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.tooltip',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
            ],
        ],
        'word' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.word',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255,
                'required' => true,
            ],
        ],
        'synonyms' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.synonyms',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 3,
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'short_description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.short_description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 3,
                'eval' => 'trim',
                'max' => 255,
            ],
        ],
        'description' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.description',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 8,
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
            ],
        ],
    ],
];
