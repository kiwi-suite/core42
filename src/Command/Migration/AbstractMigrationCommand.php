<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

namespace Core42\Command\Migration;

use Core42\Command\AbstractCommand;
use Core42\Model\Migration;
use Core42\Stdlib\Filesystem;
use Core42\TableGateway\MigrationTableGateway;
use Zend\Console\Console;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Source\Factory;

abstract class AbstractMigrationCommand extends AbstractCommand
{
    /**
     * @var array|null
     */
    private $migrationConfig;

    /**
     * @return array|null
     */
    protected function getMigrationConfig()
    {
        if ($this->migrationConfig === null) {
            $config = $this->getServiceManager()->get('config');
            $this->migrationConfig = $config['migration'];
        }

        return $this->migrationConfig;
    }

    /**
     * @throws \Exception
     */
    protected function setupTable()
    {
        /** @var Adapter $adapter */
        $adapter = $this->getServiceManager()->get('Db\Master');

        $tableName = "core42_migration";

        $metadata = Factory::createSourceFromAdapter($adapter);
        if (\in_array($tableName, $metadata->getTableNames())) {
            return;
        }

        switch ($adapter->getPlatform()->getName()) {
            case 'MySQL':
                $sql = 'CREATE TABLE `' . $tableName . '` '
                    . '(`name` VARCHAR(255) NOT NULL, `created` DATETIME NOT NULL, PRIMARY KEY (`name`))';
                break;
            default:
                throw new \Exception("'" . $adapter->getPlatform()->getName() . "' isn't support by migrations");
        }

        $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
    }

    /**
     * @return array
     */
    protected function getMigrationDirectories()
    {
        $migrationConfig = $this->getMigrationConfig();

        return \array_map(function ($dir) {
            $dir = \rtrim($dir, '/') . '/';

            if (Console::isWindows()) {
                $dir = \str_replace('\\', '/', $dir);
            }

            do {
                $dir = \preg_replace(
                    ['#//|/\./#', '#/([^/]*)/\.\./#'],
                    '/',
                    $dir,
                    -1,
                    $count
                );
            } while ($count > 0);

            if (Filesystem::isAbsolutePath($dir)) {
                $cwd = \getcwd() . DIRECTORY_SEPARATOR;
                if (Console::isWindows()) {
                    $cwd = \str_replace('\\', '/', $cwd);
                }
                $dir = \str_replace($cwd, '', $dir);
            }

            return $dir;
        }, $migrationConfig['directory']);
    }

    /**
     * @return array
     */
    protected function getAllMigrations()
    {
        /** @var \Core42\TableGateway\MigrationTableGateway $migrationTableGateway */
        $migrationTableGateway = $this->getTableGateway(MigrationTableGateway::class);
        $resultSet = $migrationTableGateway->select();

        $migratedMigrations = [];
        foreach ($resultSet as $mig) {
            /* @var Migration $mig */
            $migratedMigrations[$mig->getName()] = $mig;
        }

        $migrationDirs = $this->getMigrationDirectories();

        $migrations = [];

        foreach ($migrationDirs as $dir) {
            $globPattern = $dir . '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]t[0-9][0-9][0-9][0-9][0-9][0-9].php';
            foreach (\glob($globPattern) as $filename) {
                require_once $filename;
                $class = $this->getClassnameByFilename(\pathinfo($filename, PATHINFO_FILENAME));

                $name = $this->getMigrationNameByFilename(\pathinfo($filename, PATHINFO_FILENAME));

                $migrations[] = [
                    'name'      => $name,
                    'filename'  => $filename,
                    'instance'  => new $class(),
                    'migrated'  => (isset($migratedMigrations[$name])) ? $migratedMigrations[$name] : null,
                ];
            }
        }

        \usort($migrations, function ($array1, $array2) {
            return ((int) $array1['name'] < (int) $array2['name']) ? -1 : 1;
        });

        return $migrations;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getMigrationNameByFilename($filename)
    {
        return \str_replace(['-', 't'], '', $filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getClassnameByFilename($filename)
    {
        return 'Migration' . \str_replace(['-', 't'], '', $filename);
    }
}
