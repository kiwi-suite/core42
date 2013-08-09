<?php
namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Core42\Db\ResultSet\ResultSet;

class RowGatewayFeature extends AbstractFeature
{
    private $rowGatewayDefinition;

    private $modelPrototype;

    public function __construct($rowGatewayDefinition, $modelPrototype)
    {
        $this->rowGatewayDefinition = $rowGatewayDefinition;
        $this->modelPrototype = $modelPrototype;
    }

    public function postInitialize()
    {
        $metadata = $this->tableGateway->featureSet->getFeatureByClassName('Zend\Db\TableGateway\Feature\MetadataFeature');
        $primaryKey = $metadata->sharedData['metadata']['primaryKey'];

        $className = $this->rowGatewayDefinition;

        $rowGateway = new $className($primaryKey, $this->tableGateway->table, $this->modelPrototype, $this->tableGateway->adapter);

        $this->tableGateway->resultSetPrototype = new ResultSet(ResultSet::TYPE_ARRAYOBJECT, $rowGateway);
    }
}
