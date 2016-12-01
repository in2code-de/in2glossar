<?php

return array(
    'ctrl' => array(
        'title' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition',
        'label' => 'word',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => array(
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ),
        'searchFields' => 'word,short_description,description',
        'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('in2glossar') . 'Resources/Public/Icons/definition.svg'
    ),
    'interface' => array(
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, word, synonyms, short_description, description',
    ),
    'types' => array(
        '1' => array('showitem' => '
                sys_language_uid;;;;1-1-1,
                l10n_parent,
                l10n_diffsource,
                hidden;;1,
                tooltip,
                word,
                synonyms,
                short_description,
                description,
            --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
                starttime,
                endtime'
        ),
    ),
    'palettes' => array(
        '1' => array('showitem' => ''),
    ),
    'columns' => array(
        'sys_language_uid' => array(
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => array(
                'type' => 'select',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => array(
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
                    array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
                ),
            ),
        ),
        'l10n_parent' => array(
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => array(
                'type' => 'select',
                'items' => array(
                    array('', 0),
                ),
                'foreign_table' => 'tx_in2glossar_domain_model_definition',
                'foreign_table_where' => 'AND tx_in2glossar_domain_model_definition.pid=###CURRENT_PID### AND tx_in2glossar_domain_model_definition.sys_language_uid IN (-1,0)',
            ),
        ),
        'l10n_diffsource' => array(
            'config' => array(
                'type' => 'passthrough',
            ),
        ),
        'hidden' => array(
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'starttime' => array(
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'endtime' => array(
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => array(
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => array(
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ),
            ),
        ),
        'tooltip' => array(
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.tooltip',
            'config' => array(
                'type' => 'check',
            ),
        ),
        'word' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.word',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'max' => 255,
            ),
        ),
        'synonyms' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.synonyms',
            'config' => array(
                'type' => 'text',
                'cols' => 30,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'short_description' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.short_description',
            'config' => array(
                'type' => 'text',
                'cols' => 30,
                'rows' => 3,
                'eval' => 'trim',
            ),
        ),
        'description' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_db.xlf:tx_in2glossar_domain_model_definition.description',
            'config' => array(
                'type' => 'text',
                'cols' => 30,
                'rows' => 8,
            ),
            'defaultExtras' => 'richtext[]'
        ),
    ),
);
