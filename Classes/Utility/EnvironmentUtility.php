<?php
declare(strict_types=1);
namespace In2code\In2glossar\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * Class EnvironmentUtility
 */
class EnvironmentUtility
{
    /**
     * @return array
     */
    public static function getExcludedTagNames(): array
    {
        $configuration = self::getExtensionConfiguration();
        return GeneralUtility::trimExplode(',', (string)$configuration['excludedTagNames'], true);
    }
    /**
     * @return array
     */
    public static function getExcludedClassNames(): array
    {
        $configuration = self::getExtensionConfiguration();
        return GeneralUtility::trimExplode(',', (string)$configuration['excludedClassNames'], true);
    }

    /**
     * @return int
     */
    public static function getTypeNum(): int
    {
        return (int)self::getTyposcriptFrontendController()->type;
    }

    /**
     * @return bool
     */
    public static function isDefaultTypeNum(): bool
    {
        return self::getTypeNum() === 0;
    }

    /**
     * @return TypoScriptFrontendController
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function getTyposcriptFrontendController(): ?TypoScriptFrontendController
    {
        return array_key_exists('TSFE', $GLOBALS) ? $GLOBALS['TSFE'] : null;
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @return array
     */
    protected static function getExtensionConfiguration(): array
    {
        $configuration = [];
        if (self::isTypo3OlderThen9()) {
            $configVariables = self::getTypo3ConfigurationVariables();
            // @extensionScannerIgnoreLine We still need to access extConf for TYPO3 8.7
            $possibleConfig = unserialize((string)$configVariables['EXT']['extConf']['in2glossar']);
            if (!empty($possibleConfig) && is_array($possibleConfig)) {
                $configuration = $possibleConfig;
            }
        } else {
            // @codeCoverageIgnoreStart
            $configuration = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('powermail');
            // @codeCoverageIgnoreEnd
        }
        return $configuration;
    }

    /**
     * Get extension configuration from LocalConfiguration.php
     *
     * @return array
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    protected static function getTypo3ConfigurationVariables(): array
    {
        return (array)$GLOBALS['TYPO3_CONF_VARS'];
    }

    /**
     * Decide if TYPO3 8.7 is used (true) or newer TYPO3 (false)
     *
     * @return bool
     * @codeCoverageIgnore
     */
    protected static function isTypo3OlderThen9(): bool
    {
        return VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < 9000000;
    }
}
