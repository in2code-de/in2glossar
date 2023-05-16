<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(
    function () {
        if (TYPO3_MODE === 'BE') {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                'In2code.In2glossar',
                'web',
                'mod1',
                '',
                [
                    'Backend' => 'index',
                ],
                [
                    'access' => 'user,group',
                    'icon' => 'EXT:in2glossar/Resources/Public/Icons/Extension.svg',
                    'labels' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_mod.xlf',
                ]
            );
        }
    }
);
