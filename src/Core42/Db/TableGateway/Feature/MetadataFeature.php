<?php
namespace Core42\Db\TableGateway\Feature;

use Zend\Db\TableGateway\Feature\MetadataFeature as ZendMetadataFeature;
use Core42\Db\RowGateway\RowGateway;
use Core42\Hydrator\Strategy\MysqlDatetimeStrategy;
use Core42\Hydrator\Strategy\MysqlBooleanStrategy;

class MetadataFeature extends ZendMetadataFeature
{

    public function postInitialize()
    {
        parent::postInitialize();

        $columns = $this->metadata->getColumns($this->tableGateway->getTable());
        $platformName = $this->tableGateway->getAdapter()->getPlatform()->getName();

        foreach ($columns as $_column) {
            /* @var $_column \Zend\Db\Metadata\Object\ColumnObject */
            switch (true) {
            	case ($platformName == 'MySQL' && $_column->getDataType() == 'datetime'):
                    $rowGateway = $this->tableGateway->getResultSetPrototype()->getArrayObjectPrototype();
                    if ($rowGateway instanceof RowGateway) {
                        $rowGateway->addHydratorStrategy($_column->getName(), new MysqlDatetimeStrategy());
                    }
            	    break;
            	case ($platformName == 'MySQL' && $_column->getDataType() == "bit" && $_column->getNumericPrecision() == 1):
            	    $rowGateway = $this->tableGateway->getResultSetPrototype()->getArrayObjectPrototype();
            	    if ($rowGateway instanceof RowGateway) {
            	        $rowGateway->addHydratorStrategy($_column->getName(), new MysqlBooleanStrategy());
            	    }
            	    break;
            	default:
                    break;
            }
        }
    }
}