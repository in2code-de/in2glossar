<?php

declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Definition extends AbstractEntity
{
    const TABLE_NAME = 'tx_in2glossar_domain_model_definition';
    protected bool $tooltip = false;
    protected string $word = '';
    protected string $synonyms = '';
    protected string $shortDescription = '';
    protected string $description = '';

    public function getTooltip(): bool
    {
        return $this->tooltip;
    }

    public function setTooltip(bool $tooltip): void
    {
        $this->tooltip = $tooltip;
    }

    public function getWord(): string
    {
        return $this->word;
    }

    public function setWord(string $word): void
    {
        $this->word = $word;
    }

    public function getSynonyms(): string
    {
        return $this->synonyms;
    }

    public function setSynonyms(string $synonyms): void
    {
        $this->synonyms = $synonyms;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
