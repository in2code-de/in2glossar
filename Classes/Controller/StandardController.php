<?php
namespace In2code\In2glossar\Controller;

use In2code\In2glossar\Domain\Repository\DefinitionRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class StandardController
 */
class StandardController extends ActionController
{
    /**
     * @var DefinitionRepository
     */
    protected $definitionRepository;

    /**
     * @param DefinitionRepository $definitionRepository
     */
    public function injectDefinitionRepository(DefinitionRepository $definitionRepository)
    {
        $this->definitionRepository = $definitionRepository;
    }

    /**
     * @return void
     */
    public function listAction()
    {
        $definitions = $this->definitionRepository->findAll();
        $this->view->assign('definitions', $definitions);
    }
}
