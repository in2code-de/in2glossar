<?php

declare(strict_types=1);

namespace In2code\In2glossar\Controller;

use In2code\In2glossar\Domain\Repository\DefinitionRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class StandardController extends ActionController
{
    public function __construct(
        protected readonly DefinitionRepository $definitionRepository,
        protected readonly ExtensionConfiguration $extensionConfiguration,
    ) {}

    public function listAction(): ResponseInterface
    {
        $definitions = $this->definitionRepository->findAll();
        $this->view->assign('definitions', $definitions);
        $this->view->assign('extConf', $this->extensionConfiguration->get('in2glossar'));
        return $this->htmlResponse();
    }
}
