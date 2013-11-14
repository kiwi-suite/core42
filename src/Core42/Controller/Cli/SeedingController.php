<?php
namespace Core42\Controller\Cli;

use Core42\Command\Seeding\MakeCommand;
use Core42\Command\Seeding\SeedCommand;
use Zend\Mvc\Controller\AbstractActionController;

class SeedingController extends AbstractActionController
{

    /**
     *
     */
    public function makeAction()
    {
        $cmd = new MakeCommand();
        $cmd->setName($this->getRequest()->getParam('name', ""))
                ->run();
    }

    /**
     *
     */
    public function seedAction()
    {
        $cmd = new SeedCommand();
        $cmd->run();
    }
}
