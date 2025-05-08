<?php

declare(strict_types=1);

namespace In2code\In2glossar\Updates;

use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\AbstractListTypeToCTypeUpdate;

#[UpgradeWizard('in2codeIn2glossarCTypeMigration')]
final class In2codeIn2glossarCTypeMigration extends AbstractListTypeToCTypeUpdate
{
    public function getTitle(): string
    {
        return 'Migrate "In2code In2glossar" plugins to content elements.';
    }

    public function getDescription(): string
    {
        return 'The "In2code In2glossar" plugins are now registered as content element. Update migrates existing records and backend user permissions.';
    }

    /**
     * This must return an array containing the "list_type" to "CType" mapping
     *
     *  Example:
     *
     *  [
     *      'pi_plugin1' => 'pi_plugin1',
     *      'pi_plugin2' => 'new_content_element',
     *  ]
     *
     * @return array<string, string>
     */
    protected function getListTypeToCTypeMapping(): array
    {
        return [
            'unierfurt_glossarysearch' => 'unierfurt_glossarysearch',
        ];
    }
}
