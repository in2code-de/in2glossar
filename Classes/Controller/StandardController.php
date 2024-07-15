<?php

declare(strict_types=1);

namespace In2code\In2glossar\Controller;

use In2code\In2glossar\Domain\Repository\DefinitionRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class StandardController extends ActionController
{
    protected DefinitionRepository $definitionRepository;

    public function __construct(DefinitionRepository $definitionRepository)
    {
        $this->definitionRepository = $definitionRepository;
    }

    public function listAction(): void
    {
        $definitions = $this->definitionRepository->findAll();
        $this->view->assign('definitions', $definitions);
    }
}
