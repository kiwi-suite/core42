<?php
namespace Core42\Db\ResultSet;

use Zend\Db\ResultSet\HydratingResultSet;

class ResultSet extends HydratingResultSet
{

    /**
     *
     * @return \Core42\Db\ResultSet\ResultSet
     */
    public function filter(\Closure $closure)
    {
        $data = array();

        foreach ($this as $obj) {
            if ($closure($obj)) {
                $data[] = $obj;
            }
        }
        $resultSet = new self();
        $resultSet->initialize($data);
        return $resultSet;
    }
}
