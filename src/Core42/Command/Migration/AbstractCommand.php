<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Migration;

use Symfony\Component\Filesystem\Filesystem;
use Zend\Db\Adapter\Adapter;

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

        $metadata = $this->getServiceManager()->get('Metadata');
        if (in_array($migrationConfig['table_name'], $metadata->getTableNames())) {
            return;
        }

        /** @var Adapter $adapter */
        $adapter = $this->getServiceManager()->get('Db\Master');

        switch ($adapter->getPlatform()->getName()) {
            case 'MySQL':
                $sql = "CREATE TABLE `".$migrationConfig['table_name']."` "
                    ."(`name` VARCHAR(255) NOT NULL, `created` DATETIME NOT NULL, PRIMARY KEY (`name`))";
                break;
            default:
                throw new \Exception("'".$adapter->getPlatform()->getName()."' isn't support by migrations");
        }

        $adapter->query($sql, Adapter::QUERY_MODE_EXECUTE);
        $metadata->refresh();
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
                    array('#//|/\./#', '#/([^/]*)/\.\./#'),
                    '/',
                    $dir,
                    -1,
                    $count
                );
            } while ($count > 0);

            $filesystem = new Filesystem();
            if ($filesystem->isAbsolutePath($dir)) {
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
        $migrationTableGateway = $this->getServiceManager()->get('TableGateway')->get('Core42\Migration');
        $resultSet = $migrationTableGateway->select();

        $migratedMigrations = array();
        foreach ($resultSet as $mig) {
            $migratedMigrations[$mig->getName()] = $mig;
        }

        $migrationDirs = $this->getMigrationDirectories();

        $migrations = array();

        foreach ($migrationDirs as $dir) {
            $globPattern = $dir  . '[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]t[0-9][0-9][0-9][0-9][0-9][0-9].php';
            foreach (glob($globPattern) as $filename) {
                require_once $filename;
                $class = $this->getClassnameByFilename(pathinfo($filename, PATHINFO_FILENAME));

                $name = $this->getMigrationNameByFilename(pathinfo($filename, PATHINFO_FILENAME));

                $migrations[] = array(
                    'name'      => $name,
                    'filename'  => $filename,
                    'instance'  => new $class,
                    'migrated'  => (isset($migratedMigrations[$name])) ? $migratedMigrations[$name] : null,
                );
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
        return str_replace(array('-', 't'), "", $filename);
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getClassnameByFilename($filename)
    {
        return 'Migration' . str_replace(array('-', 't'), "", $filename);
    }
}
