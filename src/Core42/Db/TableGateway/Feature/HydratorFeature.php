<?php
namespace Core42\Db\TableGateway\Feature;

use Core42\Db\RowGateway\RowGateway;
use Core42\Hydrator\Strategy\BooleanStrategy;
use Core42\Hydrator\Strategy\DatetimeStrategy;
use Zend\Db\TableGateway\Feature\AbstractFeature;
use Zend\Db\Metadata\MetadataInterface;

class HydratorFeature extends AbstractFeature
{
    /**
     * @var MetadataInterface
     */
    protected $metadata = null;

    /**
     * Constructor
     *
     * @param MetadataInterface $metadata
     */
    public function __construct(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    public function postInitialize()
    {
        $columns = $this->metadata->getColumns($this->tableGateway->getTable());

        foreach ($columns as $_column) {
            /* @var $_column \Zend\Db\Metadata\Object\ColumnObject */
            switch (true) {
            	case ($_column->getDataType() == 'datetime'):
                    $rowGateway = $this->tableGateway->getResultSetPrototype()->getArrayObjectPrototype();
                    if ($rowGateway instanceof RowGateway) {
                        $rowGateway->addHydratorStrategy($_column->getName(), new DatetimeStrategy());
                    }
            	    break;
            	case ($_column->getDataType() == "bit" && $_column->getNumericPrecision() == 1):
            	    $rowGateway = $this->tableGateway->getResultSetPrototype()->getArrayObjectPrototype();
            	    if ($rowGateway instanceof RowGateway) {
            	        $rowGateway->addHydratorStrategy($_column->getName(), new BooleanStrategy());
            	    }
            	    break;
            	default:
                    break;
            }
        }
    }
}
