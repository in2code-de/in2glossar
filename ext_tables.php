<?php

use In2code\In2glossar\Controller\BackendController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::registerModule(
    'In2glossar',
    'web',
    'mod1',
    '',
    [
        BackendController::class => 'index',
    ],
    [
        'access' => 'user,group',
        'icon' => 'EXT:in2glossar/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:in2glossar/Resources/Private/Language/locallang_mod.xlf',
    ],
);
