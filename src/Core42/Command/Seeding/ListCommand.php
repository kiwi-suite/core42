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
use ZF\Console\Route;

class ListCommand extends AbstractCommand implements ConsoleAwareInterface
{

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
    protected function execute()
    {
        $seedingList = $this->getAllSeeds();

        foreach ($seedingList as $seeding) {
            if ($seeding['seeded'] === null) {
                $this->consoleOutput("<comment>" .$seeding['name'].' (not seeded)</comment>');
            } else {
                $seedingDate = $seeding['seeded']->getCreated()->format('Y-m-d H:i:s');
                $this->consoleOutput(
                    "<info>{$seeding['name']} ({$seedingDate})</info>"
                );
            }

            $this->consoleOutput($seeding['filename']);
            $this->consoleOutput("");
        }

    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {

    }
}
