<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class DefinitionRepository extends Repository
{
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
