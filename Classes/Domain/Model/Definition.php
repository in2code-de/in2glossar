<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Definition extends AbstractEntity
{
    const TABLE_NAME = 'tx_in2glossar_domain_model_definition';
    public bool $tooltip = false;
    public string $word = '';
    public string $synonyms = '';
    public string $shortDescription = '';
    public string $description = '';
}
