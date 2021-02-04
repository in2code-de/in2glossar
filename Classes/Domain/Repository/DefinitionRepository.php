<?php
namespace In2code\In2glossar\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Class DefinitionRepository
 */
class DefinitionRepository extends Repository
{
    /**
     * @return void
     */
    public function initializeObject()
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }
}
