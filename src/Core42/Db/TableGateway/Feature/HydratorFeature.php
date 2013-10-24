<?php
namespace Core42\Db\TableGateway\Feature;

use Core42\Hydrator\Strategy\Database\BooleanStrategy;
use Core42\Hydrator\Strategy\Database\DatetimeStrategy;
use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\Metadata\MetadataInterface;
use Zend\ServiceManager\ServiceManager;

class HydratorFeature extends AbstractFeature
{
    /**
     * @var MetadataInterface
     */
    protected $metadata = null;

    /**
     * @var ServiceManager
     */
    protected $serviceManager = null;

    /**
     *
     * @param MetadataInterface $metadata
     */
    public function __construct(MetadataInterface $metadata, ServiceManager $serviceManager)
    {
        $this->metadata = $metadata;
        $this->serviceManager = $serviceManager;
    }

    /**
     *
     */
    public function postInitialize()
    {
        $columns = $this->metadata->getColumns($this->tableGateway->getTable());

        foreach ($columns as $_column) {
            /* @var $_column \Zend\Db\Metadata\Object\ColumnObject */
            $strategy = $this->serviceManager->get('Core42\Hydrator\Strategy\Database\PluginManager')->getStrategy($_column);
            $this->tableGateway->getHydrator()->addStrategy($_column->getName(), $strategy);
        }
    }
}
