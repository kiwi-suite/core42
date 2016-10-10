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

namespace Core42\Command\Migration;

use Core42\Model\Migration;
use Core42\Stdlib\Filesystem;
use Core42\TableGateway\MigrationTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Source\Factory;

abstract class AbstractCommand extends \Core42\Command\AbstractCommand
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
        $migrationConfig = $this->getMigrationConfig();

        /** @var Adapter $adapter */
        $adapter = $this->getServiceManager()->get('Db\Master');


        $metadata = Factory::createSourceFromAdapter($adapter);
        if (in_array($migrationConfig['table_name'], $metadata->getTableNames())) {
            return;
        }

        switch ($adapter->getPlatform()->getName()) {
            case 'MySQL':
                $sql = 'CREATE TABLE `' . $migrationConfig['table_name'] . '` '
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

        return array_map(function ($dir) {
            $dir = rtrim($dir, '/') . '/';

            do {
                $dir = preg_replace(
                    ['#//|/\./#', '#/([^/]*)/\.\./#'],
                    '/',
                    $dir,
                    -1,
                    $count
                );
            } while ($count > 0);

            if (Filesystem::isAbsolutePath($dir)) {
                $dir = str_replace(getcwd() . DIRECTORY_SEPARATOR, '', $dir);
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
        $migrationTableGateway = $this->getServiceManager()->get('TableGateway')->get(MigrationTableGateway::class);
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
            foreach (glob($globPattern) as $filename) {
                require_once $filename;
                $class = $this->getClassnameByFilename(pathinfo($filename, PATHINFO_FILENAME));

                $name = $this->getMigrationNameByFilename(pathinfo($filename, PATHINFO_FILENAME));

                $migrations[] = [
                    'name'      => $name,
                    'filename'  => $filename,
                    'instance'  => new $class,
                    'migrated'  => (isset($migratedMigrations[$name])) ? $migratedMigrations[$name] : null,
                ];
            }
        }

        return $migrations;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getMigrationNameByFilename($filename)
    {
        return str_replace(['-', 't'], '', $filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getClassnameByFilename($filename)
    {
        return 'Migration' . str_replace(['-', 't'], '', $filename);
    }
}
