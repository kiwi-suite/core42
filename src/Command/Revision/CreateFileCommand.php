<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\Command\Revision;

use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Ramsey\Uuid\Uuid;
use Zend\Json\Json;
use ZF\Console\Route;

class CreateFileCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @var bool
     */
    protected $transaction = false;

    /**
     *
     */
    protected function execute()
    {
        $result = [];

        $result['revision_type'] = 'none';
        $result['revision_hash'] = md5(Uuid::uuid4()->toString());
        $rev = $this->getGitRevision();
        if ($rev !== false) {
            $result['revision_type'] = 'git';
            $result['revision_hash'] = $rev;
        }

        $result['revision_hash_short'] = substr($result['revision_hash'], 0, 7);

        if (!is_dir('resources/version')) {
            @mkdir('resources/version', 0777, true);
        }

        file_put_contents('resources/version/revision.json', Json::encode($result, false, ['prettyPrint' => true]));

        return $result;
    }

    protected function getGitRevision()
    {
        if (!is_dir(getcwd() . '/.git/')) {
            return false;
        }

        if (!file_exists(getcwd() . '/.git/HEAD')) {
            return false;
        }

        $ref = trim(file_get_contents(getcwd() . '/.git/HEAD'));
        if (substr($ref, 0 , 5) != 'ref: ') {
            return false;
        }
        $ref = trim(substr($ref, 5));

        if (!file_exists(getcwd() . '/.git/' . $ref)) {
            return false;
        }

        return trim(file_get_contents(getcwd() . '/.git/' . $ref));
    }

    /**
     * @param Route $route
     */
    public function consoleSetup(Route $route)
    {

    }
}
