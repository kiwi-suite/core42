<?php
namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\Metadata\MetadataInterface;

class HydratorFeature extends AbstractFeature
{
    /**
     * @var MetadataInterface
     */
    protected $metadata = null;

    protected $hydratorStrategyPluginManager;

    /**
     *
     * @param MetadataInterface $metadata
     */
    public function __construct(MetadataInterface $metadata, $hydratorStrategyPluginManager)
    {
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
