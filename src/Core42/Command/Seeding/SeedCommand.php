<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Seeding;

use Core42\Command\ConsoleAwareInterface;
use Core42\Model\Migration;
use Core42\Model\Seeding;
use Core42\TableGateway\SeedingTableGateway;
use Zend\I18n\Validator\Alnum;
use ZF\Console\Route;

class SeedCommand extends AbstractCommand implements ConsoleAwareInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $force = false;

    /**
     * @var mixed
     */
    private $instance;

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param boolean $force
     * @return $this
     */
    public function setForce($force)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function configure()
    {
        $this->setupTable();
    }

    /**
     *
     */
    protected function preExecute()
    {
        if (empty($this->name)) {
            $this->addError("name", "name isn't set");

            return;
        }

        $alnum = new Alnum();
        if (!$alnum->isValid($this->name)) {
            $this->addError("name", "name '".$this->name."' isn't valid");

            return;
        }

        $seeds = $this->getAllSeeds();
        foreach ($seeds as $_seed) {
            if ($_seed['name'] != $this->name) {
                continue;
            }

            if ($_seed['seeded'] !== null && $this->force === false) {
                $this->addError("name", "seeding '".$this->name."' already seeded");

                return;
            }

            $this->instance = $_seed['instance'];
            break;
        }

        if ($this->instance === null) {
            $this->addError("name", "seeding with name '".$this->name."' not found");

            return;
        }
    }

    /**
     *
     */
    protected function execute()
    {
        $this->instance->seed($this->getServiceManager());

        /** @var SeedingTableGateway $seedingTableGateway */
        $seedingTableGateway = $this->getServiceManager()->get('TableGateway')->get('Core42\Seeding');

        $seeding = $seedingTableGateway->selectByPrimary($this->name);

        if (empty($seeding)) {
            $seeding = new Seeding();
            $seeding->setName($this->name)
                ->setCreated(new \DateTime());

            $seedingTableGateway->insert($seeding);

            $this->consoleOutput("Seeding '{$this->name}' seeded");

            return;
        }

        $seeding->setCreated(new \DateTime());
        $seedingTableGateway->update($seeding);

        $this->consoleOutput("Seeding '{$this->name}' seeded");
    }

    /**
     * @param Route $route
     * @return mixed|void
     */
    public function consoleSetup(Route $route)
    {
        $this->setName($route->getMatchedParam('name'));
        $this->setForce($route->getMatchedParam("f", false));
    }
}
