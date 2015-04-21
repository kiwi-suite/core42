<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Selector;

use Core42\Db\ResultSet\ResultSet;
use Core42\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

abstract class AbstractTableGatewaySelector extends AbstractSelector
{
    /**
     * @var string
     */
    protected $tableGateway;

    /**
     * @var null|array
     */
    protected $columns = null;

    /**
     * @return Select
     */
    protected function getSelect()
    {
        return $this->getTableGateway($this->tableGateway)->getSql()->select();
    }

    /**
     * @return ResultSet
     */
    public function getResult()
    {
        $select = $this->getSelect();
        if ($this->columns !== null) {
            $select->columns($this->columns);
        }

        return $this->getTableGateway($this->tableGateway)->select($select);
    }

    /**
     * @param string $tableGatewayName
     * @return AbstractTableGateway
     */
    public function getTableGateway($tableGatewayName)
    {
        return $this->getServiceManager()->get('TableGateway')->get($tableGatewayName);
    }
}
