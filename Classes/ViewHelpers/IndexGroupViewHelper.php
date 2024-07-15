<?php
declare(strict_types=1);
namespace In2code\In2glossar\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * IndexGroupViewHelper
 */
class IndexGroupViewHelper extends AbstractViewHelper
{
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;

    /**
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('definitionGroups', 'array', 'The iteratable object containing defintion groups', true);
        $this->registerArgument('indexGroups', 'array', 'The groups', false, [
            'a-d' => 'a,b,c,d',
            'e-h' => 'e,f,g,h',
            'i-l' => 'i,j,k,l',
            'm-p' => 'm,n,o,p',
            'q-t' => 'q,r,s,t',
            'u-z' => 'u,v,w,x,y,z',
        ]);
        $this->registerArgument('as', 'string', '', true);
    }

    /**
     * @return string
     */
    public function render()
    {
        $groups = $this->groupDefinitions();
        $this->templateVariableContainer->add($this->arguments['as'], $groups);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($this->arguments['as']);
        return $output;
    }

    /**
     * @return array
     */
    protected function groupDefinitions()
    {
        $group = array();
        $definitionGroups = $this->arguments['definitionGroups'];
        foreach ($this->arguments['indexGroups'] as $indexGroupKey => $indexGroup) {
            $group[$indexGroupKey] = [];
            $subgroups = GeneralUtility::trimExplode(',', $indexGroup);
            foreach ($subgroups as $subgroup) {
                $subgroup = strtolower($subgroup);
                if (is_array($definitionGroups[$subgroup])) {
                    $group[$indexGroupKey] = array_merge(
                        $group[$indexGroupKey],
                        $definitionGroups[$subgroup]
                    );
                }
            }
        }
        return $group;
    }
}
