<?php
declare(strict_types=1);

namespace In2code\In2glossar\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Class Definition
 */
class Definition extends AbstractEntity
{
    const TABLE_NAME = 'tx_in2glossar_domain_model_definition';

    /**
     * @var bool
     */
    protected $tooltip;

    /**
     * @var string
     */
    protected $word;

    /**
     * @var string
     */
    protected $synonyms;

    /**
     * @var string
     */
    protected $shortDescription;

    /**
     * @var string
     */
    protected $description;

    /**
     * @return boolean
     */
    public function getTooltip()
    {
        return $this->tooltip;
    }

    /**
     * @param boolean $tooltip
     */
    public function setTooltip($tooltip)
    {
        $this->tooltip = $tooltip;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param string $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return string
     */
    public function getSynonyms()
    {
        return $this->synonyms;
    }

    /**
     * @param string $synonyms
     */
    public function setSynonyms($synonyms)
    {
        $this->synonyms = $synonyms;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
