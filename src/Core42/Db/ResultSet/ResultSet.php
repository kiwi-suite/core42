<?php
namespace Core42\Db\ResultSet;

use Zend\Db\ResultSet\HydratingResultSet;

class ResultSet extends HydratingResultSet
{
    
	/**
	 * 
	 * @return \Core42\Db\ResultSet\ResultSet
	 */
	public function filter()
	{
		$resultSet = new self();
		
		return $resultSet;
	}
	
	public function save()
	{
		foreach ($this as $obj) {
			if ($obj instanceof )
		}
	}
}
