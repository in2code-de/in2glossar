<?php

declare(strict_types=1);

namespace In2code\In2glossar\Utility;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

class EnvironmentUtility
{
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
}
