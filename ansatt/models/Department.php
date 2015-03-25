<?php
require_once("Model.php");

class Department extends Model {
	
	private $table = "departments";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'title',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => true
								),
							array(
								'name' => 'short_title',
								'type' => 'varchar',
								'length' => 100,
								'NULL' => true
								)
						);
	
	public function __construct($db, $params=null)
	{	
		parent::__construct($db, $params);
	}
	
	public function __get($key)
	{
		return $this->$key;
	}
	
	public function __set($key, $value)
	{
		$this->$key = $value;
	}

}

?>