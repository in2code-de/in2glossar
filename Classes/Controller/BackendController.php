<?php

declare(strict_types=1);

namespace In2code\In2glossar\Controller;

use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BackendController extends ActionController
{
    public function initializeAction(): void
    {
        if ($this->isStoragePidGiven() === false) {
            $this->addFlashMessage('Storage Pid is not set', '', AbstractMessage::ERROR);
        }
    }

    public function indexAction(): void {}

    /**
     * @param string $messageBody
     * @param string $messageTitle
     * @param int $severity
     * @param bool $storeInSession
     */
    public function addFlashMessage(
        $messageBody,
        $messageTitle = '',
        $severity = AbstractMessage::OK,
        $storeInSession = true
    ): void {
        /** @var FlashMessage $message */
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $messageBody,
            $messageTitle,
            $severity,
            $storeInSession,
        );
        /* @var $flashMessageService FlashMessageService */
        $flashMessageService = GeneralUtility::makeInstance(
            FlashMessageService::class,
        );
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }

    protected function isStoragePidGiven(): bool
    {
        return MathUtility::canBeInterpretedAsInteger($this->settings['storagePid'])
            && $this->settings['storagePid'] > 0;
    }
}
