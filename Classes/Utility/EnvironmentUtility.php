<?php

declare(strict_types=1);

namespace In2code\In2glossar\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class EnvironmentUtility
{
    public static function getExcludedTagNames(): array
    {
        $configuration = self::getExtensionConfiguration();
        return GeneralUtility::trimExplode(',', (string) $configuration['excludedTagNames'], true);
    }

    public static function getExcludedClassNames(): array
    {
        $configuration = self::getExtensionConfiguration();
        return GeneralUtility::trimExplode(',', (string) $configuration['excludedClassNames'], true);
    }

    public static function getTypeNum(): int
    {
        return (int) self::getTyposcriptFrontendController()->type;
    }

    public static function isDefaultTypeNum(): bool
    {
        return self::getTypeNum() === 0;
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function getTyposcriptFrontendController(): ?TypoScriptFrontendController
    {
        return array_key_exists('TSFE', $GLOBALS) ? $GLOBALS['TSFE'] : null;
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     */
    protected static function getExtensionConfiguration(): array
    {
        return GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('in2glossar');
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected static function getTypo3ConfigurationVariables(): array
    {
        return (array) $GLOBALS['TYPO3_CONF_VARS'];
    }
}
