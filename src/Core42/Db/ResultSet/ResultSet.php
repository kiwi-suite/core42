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
	    
		$resultSet = new self();
		
		foreach ($this as $obj) {
		    
		}
		
		return $resultSet;
	}
	
	public function save()
	{
		foreach ($this as $obj) {
		    $obj->save();
		}
	}
}
