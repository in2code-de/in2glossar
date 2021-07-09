<?php
namespace In2code\In2glossar\ViewHelpers\Be;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Fluid\ViewHelpers\Be\TableListViewHelper as TableListViewHelperFluid;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

/**
 * TableListViewHelper
 */
class TableListViewHelper extends TableListViewHelperFluid
{
    /**
     * Renders a record list as known from the TYPO3 list module
     * Note: This feature is experimental!
     *
     * @return string the rendered record list
     * @see localRecordList
     */
    public function render()
    {
        $tableName = $this->arguments['tableName'];
        $fieldList = $this->arguments['fieldList'];
        $storagePid = $this->arguments['storagePid'];
        $levels = $this->arguments['levels'];
        $filter = $this->arguments['filter'];

        $pageinfo = BackendUtility::readPageAccess(
            GeneralUtility::_GP('id'),
            $GLOBALS['BE_USER']->getPagePermsClause(1)
        );
        $dblist = $this->getDatabaseRecordList();
        $dblist->backPath = $GLOBALS['BACK_PATH'];
        $dblist->pageRow = $pageinfo;
        $dblist->alternateBgColors = $this->arguments['alternateBackgroundColors'];
        if ($this->arguments['readOnly'] === false) {
            $dblist->calcPerms = $GLOBALS['BE_USER']->calcPerms($pageinfo);
        }
        if ($storagePid === null) {
            $storagePid = $this->getStoragePid();
        }
        $dblist->start(
            $storagePid,
            $tableName,
            (int)GeneralUtility::_GP('pointer'),
            $filter,
            $levels,
            $this->arguments['recordsPerPage']
        );
        $dblist->setFields = array($tableName => $fieldList);
        $dblist->sortField = $this->arguments['sortField'];
        $dblist->sortRev = $this->arguments['sortDescending'];
        $dblist->script = $_SERVER['REQUEST_URI'];
        $dblist->generateList();

        return $dblist->HTMLcode;
    }

    /**
     * @return DatabaseRecordList
     */
    protected function getDatabaseRecordList()
    {
        /* @var $dblist DatabaseRecordList */
        $dblist = GeneralUtility::makeInstance(DatabaseRecordList::class);

        $dblist->showClipboard = false;
        $dblist->disableSingleTableView = true;
        $dblist->clickTitleMode = $this->arguments['clickTitleMode'];
        $dblist->clickMenuEnabled = $this->arguments['enableClickMenu'];
        $dblist->allFields = true;
        $dblist->dontShowClipControlPanels = false;
        $dblist->displayFields = true;
        $dblist->noControlPanels = false;
        $dblist->localizationView = true;

        return $dblist;
    }

    /**
     * @return int|null
     */
    protected function getStoragePid()
    {
        $frameworkConfiguration = $this->configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
        );
        return $frameworkConfiguration['persistence']['storagePid'];
    }
}
