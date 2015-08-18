<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Feature;

use Core42\Hydrator\Strategy\Database\DatabaseStrategyInterface;
use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\Metadata\MetadataInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Stdlib\Hydrator\AbstractHydrator;

class HydratorFeature extends AbstractFeature
{
    /**
     * @var MetadataInterface
     */
    protected $metadata = null;

    /**
     * @var AbstractPluginManager
     */
    protected $hydratorStrategyPluginManager;

    /**
     *
     * @param MetadataInterface $metadata
     * @param AbstractPluginManager $hydratorStrategyPluginManager
     */
    public function __construct(
        MetadataInterface $metadata,
        AbstractPluginManager $hydratorStrategyPluginManager
    ) {
        $this->metadata = $metadata;
        $this->hydratorStrategyPluginManager = $hydratorStrategyPluginManager;
    }

    /**
     *
     */
    public function postInitialize()
    {
        $columns = $this->metadata->getColumns($this->tableGateway->getTable());

        $platform = $this->tableGateway->adapter->getPlatform()->getName();
        /* @var AbstractHydrator $hydrator */
        $hydrator = $this->tableGateway->getHydrator();

        $services = $this->hydratorStrategyPluginManager->getCanonicalNames(); // getRegisteredServices();

        if (!$this->tableGateway instanceof \Core42\Db\TableGateway\AbstractTableGateway) {
            throw new \Exception('HydratorFeature must only be used in Core42 TableGateways');
        }

        $databaseTypeMap = $this->tableGateway->getDatabaseTypeMap();

        foreach ($columns as $_column) {
            /* @var \Zend\Db\Metadata\Object\ColumnObject $_column */

            if (isset($databaseTypeMap[$_column->getName()])) {
                if ($databaseTypeMap[$_column->getName()] !== null) {
                    $strategy = $this->hydratorStrategyPluginManager->get(
                        $databaseTypeMap[$_column->getName()]
                    );
                    $hydrator->addStrategy($_column->getName(), $strategy);
                }
            } else {
                foreach ($services as $canonicalName => $name) {
                    if (strpos($canonicalName, $platform) == 0) {
                        /* @var DatabaseStrategyInterface $strategy */
                        $strategy = $this->hydratorStrategyPluginManager->get($canonicalName);
                        if ($strategy->isResponsible($_column)) {
                            $hydrator->addStrategy($_column->getName(), $strategy);
                        }
                    }
                }
            }
        }
    }
}
