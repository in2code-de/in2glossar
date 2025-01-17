<?php

declare(strict_types=1);

namespace In2code\In2glossar\ViewHelpers;

use In2code\In2glossar\Domain\Model\Definition;
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

    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('collection', 'array', 'The iterable object containing definitions', true);
        $this->registerArgument('as', 'string', '', true);
    }

    public function render(): string
    {
        $index = $this->buildIndex($this->arguments['collection']);
        $this->templateVariableContainer->add($this->arguments['as'], $index);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($this->arguments['as']);
        return $output;
    }

    protected function buildIndex(iterable $collection): array
    {
        $index = [];
        foreach (range('a', 'z') as $char) {
            $index[$char] = [];
        }

        foreach ($collection as $item) {
            /* @var $item Definition */
            $firstChar = strtolower(substr($item->word, 0, 1));
            $index[$firstChar][] = $item;
        }

        return $index;
    }
}
