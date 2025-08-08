<?php

use In2code\In2glossar\Controller\StandardController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

ExtensionUtility::configurePlugin('In2glossar', 'Main', [StandardController::class => 'list'], [], ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT);
