<?php
declare(strict_types=1);
namespace In2code\In2glossar\Controller;

use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;

/**
 * Class AbstractBackendController
 */
abstract class AbstractBackendController extends ActionController
{
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
    ) {
        /** @var FlashMessage $message */
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            $messageBody,
            $messageTitle,
            $severity,
            $storeInSession
        );
        /* @var $flashMessageService FlashMessageService */
        $flashMessageService = $this->objectManager->get(
            FlashMessageService::class
        );
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);
    }

    /**
     * @return bool
     */
    protected function isStoragePidGiven(): bool
    {
        return MathUtility::canBeInterpretedAsInteger($this->settings['storagePid'])
            && $this->settings['storagePid'] > 0;
    }
}
