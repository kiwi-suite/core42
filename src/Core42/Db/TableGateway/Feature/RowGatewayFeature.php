<?php
namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\ModelHydrator;

class RowGatewayFeature extends AbstractFeature
{
    private $rowGatewayDefinition;

    private $modelPrototype;

    private $hydrator;

    public function __construct($rowGatewayDefinition, $modelPrototype, ModelHydrator $hydrator)
    {
        $this->rowGatewayDefinition = $rowGatewayDefinition;
        $this->modelPrototype = $modelPrototype;
        $this->hydrator = $hydrator;
    }

    public function postInitialize()
    {
        $metadata = $this->tableGateway->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature');
        $primaryKey = $metadata->sharedData['metadata']['primaryKey'];

        $className = $this->rowGatewayDefinition;

        $rowGateway = new $className($primaryKey, $this->tableGateway->table, $this->modelPrototype, $this->tableGateway->adapter, $this->hydrator);

        $this->tableGateway->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, $rowGateway);
    }
}
