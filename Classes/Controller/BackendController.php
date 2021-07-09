<?php
declare(strict_types=1);
namespace In2code\In2glossar\Controller;

use TYPO3\CMS\Core\Messaging\AbstractMessage;

/**
 * Class BackendController
 */
class BackendController extends AbstractBackendController
{
    /**
     * @return void
     */
    public function initializeAction()
    {
        if ($this->isStoragePidGiven() === false) {
            $this->addFlashMessage('Storage Pid is not set', '', AbstractMessage::ERROR);
        }
    }

    /**
     * @return void
     */
    public function indexAction()
    {
    }
}
