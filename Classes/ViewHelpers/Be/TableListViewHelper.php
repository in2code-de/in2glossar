<?php
namespace In2code\In2glossar\ViewHelpers\Be;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Dominique Kreemers <dominique.kreemers@in2code.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList;

/**
 * TableListViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class TableListViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\TableListViewHelper
{
    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('recordsPerPage', 'integer', 'amount of records to be displayed at once. Defaults to $TCA[$tableName][\'interface\'][\'maxSingleDBListItems\'] or (if that\'s not set) to 100', false, 0);
        $this->registerArgument('clickTitleMode', 'string', 'one of "edit", "show" (only pages, tt_content), "info)', false, 'edit');
        $this->registerArgument('enableClickMenu', 'boolean', 'enables context menu', false, true);
        $this->registerArgument('readOnly', 'boolean', 'if TRUE, the edit icons won\'t be shown. Otherwise edit icons will be shown, if the current BE user has edit rights for the specified table!', false, false);
        $this->registerArgument('sortDescending', 'boolean', 'if TRUE records will be sorted in descending order', false, false);
        $this->registerArgument('alternateBackgroundColors', 'boolean', 'if set, rows will have alternate background colors', false, false);
        $this->registerArgument('sortField', 'string', 'table field to sort the results by', false, '');
    }

    /**
     * Renders a record list as known from the TYPO3 list module
     * Note: This feature is experimental!
     *
     * @param string $tableName name of the database table
     * @param array $fieldList list of fields to be displayed. If empty, only the title column (configured in $TCA[$tableName]['ctrl']['title']) is shown
     * @param integer $storagePid by default, records are fetched from the storage PID configured in persistence.storagePid. With this argument, the storage PID can be overwritten
     * @param integer $levels corresponds to the level selector of the TYPO3 list module. By default only records from the current storagePid are fetched
     * @param string $filter corresponds to the "Search String" textbox of the TYPO3 list module. If not empty, only records matching the string will be fetched
     * @return string the rendered record list
     * @see localRecordList
     */
    public function render(
        $tableName,
        array $fieldList = array(),
        $storagePid = null,
        $levels = 0,
        $filter = ''
    ) {
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
        $dblist->start($storagePid, $tableName, (int)GeneralUtility::_GP('pointer'), $filter, $levels, $this->arguments['recordsPerPage']);
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
        $dblist->clickTitleMode = $this->arguments['edit'];
        $dblist->clickMenuEnabled = $this->arguments['enableClickMenu'];
        $dblist->allFields = true;
        $dblist->dontShowClipControlPanels = false;
        $dblist->displayFields = true;
        $dblist->noControlPanels = false;

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
