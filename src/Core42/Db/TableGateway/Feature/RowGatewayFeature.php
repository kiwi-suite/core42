<?php
namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\AbstractFeature;
use Core42\Db\ResultSet\ResultSet;
use Core42\Hydrator\ModelHydrator;

class RowGatewayFeature extends AbstractFeature
{
    /**
     *
     * @var string
     */
    private $rowGatewayDefinition;

    /**
     *
     * @var string|\Core42\Model\AbstractModel
     */
    private $modelPrototype;

    /**
     *
     * @var ModelHydrator
     */
    private $hydrator;

    /**
     *
     * @param string $rowGatewayDefinition
     * @param string|AbstractModel $modelPrototype
     * @param ModelHydrator $hydrator
     */
    public function __construct($rowGatewayDefinition, $modelPrototype, ModelHydrator $hydrator)
    {
        $this->rowGatewayDefinition = $rowGatewayDefinition;
        $this->modelPrototype = $modelPrototype;

        if (is_string($this->modelPrototype)) {
            $this->modelPrototype = new $modelPrototype();
        }

        $this->hydrator = $hydrator;
    }

    /**
     *
     */
    public function postInitialize()
    {
        $metadata = $this->tableGateway->featureSet->getFeatureByClassName('Core42\Db\TableGateway\Feature\MetadataFeature');
        $primaryKey = $metadata->sharedData['metadata']['primaryKey'];

        $className = $this->rowGatewayDefinition;

        $rowGateway = new $className($primaryKey, $this->tableGateway->table, $this->modelPrototype, $this->tableGateway->adapter, $this->hydrator);

        $this->tableGateway->resultSetPrototype = new ResultSet($this->hydrator, $this->modelPrototype);
        $this->tableGateway->setRowGateway($rowGateway);
    }
}
