<?php

declare(strict_types=1);

namespace In2code\In2glossar\Controller;

use In2code\In2glossar\Domain\Repository\DefinitionRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class StandardController extends ActionController
{
    protected DefinitionRepository $definitionRepository;
    private ExtensionConfiguration $extensionConfiguration;

    public function __construct(
        DefinitionRepository $definitionRepository,
        ExtensionConfiguration $extensionConfiguration
    ) {
        $this->definitionRepository = $definitionRepository;
        $this->extensionConfiguration = $extensionConfiguration;
    }

    public function listAction(): void
    {
        $definitions = $this->definitionRepository->findAll();
        $this->view->assign('definitions', $definitions);
        $this->view->assign('extConf', $this->extensionConfiguration->get('in2glossar'));
    }
}
