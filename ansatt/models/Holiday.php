<?php
require_once("Model.php");

class Holiday extends Model {
	
	private $table = "holidays";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'date',
								'type' => 'date',
								'NULL' => true
								),
							array(
								'name' => 'name',
								'type' => 'varchar',
								'length' => 255,
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