<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command\Seeding;

use Symfony\Component\Filesystem\Filesystem;
use Zend\Db\Adapter\Adapter;
use Zend\Filter\Word\UnderscoreToCamelCase;

abstract class AbstractCommand extends \Core42\Command\AbstractCommand
{
    /**
     * @var array|null
     */
    private $seedingConfig;

    /**
     * @return array|null
     */
    protected function getSeedingConfig()
    {
        if ($this->seedingConfig === null) {
            $config = $this->getServiceManager()->get('config');
            $this->seedingConfig = $config['seeding'];
        }

        return $this->seedingConfig;
    }

    /**
     * @throws \Exception
     */
    protected function setupTable()
    {
        $seedingConfig = $this->getSeedingConfig();

        $metadata = $this->getServiceManager()->get('Metadata');
        if (in_array($seedingConfig['table_name'], $metadata->getTableNames())) {
            return;
        }

        /** @var Adapter $adapter */
        $adapter = $this->getServiceManager()->get('Db\Master');

        switch ($adapter->getPlatform()->getName()) {
            case 'MySQL':
                $sql = "CREATE TABLE `".$seedingConfig['table_name']."` "
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
    protected function getSeedingDirectories()
    {
        $seedingConfig = $this->getSeedingConfig();

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
        }, $seedingConfig['directory']);
    }

    /**
     * @return array
     */
    protected function getAllSeeds()
    {
        /** @var \Core42\TableGateway\SeedingTableGateway $seedingTableGateway */
        $seedingTableGateway = $this->getServiceManager()->get('TableGateway')->get('Core42\Seeding');
        $resultSet = $seedingTableGateway->select();

        $seededSeeding = array();
        foreach ($resultSet as $seed) {
            $seededSeeding[$seed->getName()] = $seed;
        }

        $seedingDirs = $this->getSeedingDirectories();

        $seeding = array();

        foreach ($seedingDirs as $dir) {
            $globPattern = $dir  . '*.php';
            foreach (glob($globPattern) as $filename) {
                require_once $filename;
                $name = $this->getSeedingNameByFilename(pathinfo($filename, PATHINFO_FILENAME));
                $class = 'Seeding' . ucfirst($name);

                $seeding[] = array(
                    'name'      => $name,
                    'filename'  => $filename,
                    'instance'  => new $class,
                    'seeded'  => (isset($seededSeeding[$name])) ? $seededSeeding[$name] : null,
                );
            }
        }

        return $seeding;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function getSeedingNameByFilename($filename)
    {
        $camelCaseFilter = new UnderscoreToCamelCase();
        return $camelCaseFilter->filter($filename);
    }
}
