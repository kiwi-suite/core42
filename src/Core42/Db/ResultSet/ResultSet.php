<?php
namespace Core42\Db\ResultSet;

use Zend\Db\ResultSet\ResultSet as ZendResultSet;

class ResultSet extends ZendResultSet
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

    public function save()
    {
        foreach ($this as $obj) {
            $obj->save();
        }
    }
}
