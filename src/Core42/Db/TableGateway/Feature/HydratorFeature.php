<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\TableGateway\Feature;

use Core42\Hydrator\Strategy\Database\DatabasePluginManagerInterface;
use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\Metadata\MetadataInterface;

class HydratorFeature extends AbstractFeature
{
    /**
     * @var MetadataInterface
     */
    protected $metadata = null;

    /**
     * @var DatabasePluginManagerInterface
     */
    protected $hydratorStrategyPluginManager;

    /**
     *
     * @param MetadataInterface $metadata
     * @param DatabasePluginManagerInterface $hydratorStrategyPluginManager
     */
    public function __construct(
        MetadataInterface $metadata,
        DatabasePluginManagerInterface
        $hydratorStrategyPluginManager
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

        foreach ($columns as $_column) {
            /* @var $_column \Zend\Db\Metadata\Object\ColumnObject */
            $strategy = $this->hydratorStrategyPluginManager->getStrategy($_column);
            $this->tableGateway->getHydrator()->addStrategy($_column->getName(), $strategy);
        }
    }
}
