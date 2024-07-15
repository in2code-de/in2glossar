<?php

declare(strict_types=1);

namespace In2code\In2glossar\ViewHelpers;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class IndexViewHelper extends AbstractViewHelper
{
    /**
     * @var bool
     */
    protected $escapeChildren = false;
    /**
     * @var bool
     */
    protected $escapeOutput = false;
    protected array $index = [];

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('collection', 'array', 'The iteratable object containing defintions', true);
        $this->registerArgument('as', 'string', '', true);
    }

    public function render(): string
    {
        $this->buildIndex($this->arguments['collection']);
        $this->templateVariableContainer->add($this->arguments['as'], $this->index);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($this->arguments['as']);
        return $output;
    }

    protected function buildIndex(iterable $collection): void
    {
        foreach (range('a', 'z') as $char) {
            $this->index[$char] = [];
        }
        foreach ($collection as $item) {
            /* @var $item AbstractEntity */
            $firstChar = strtolower(substr($item->_getProperty('word'), 0, 1));
            $this->index[$firstChar][] = $item;
        }
    }
}
