<?php
require_once("Model.php");

class Message extends Model {
	
	private $table = "messages";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'title',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => true
								),
							array(
								'name' => 'content',
								'type' => 'TEXT',
								'NULL' => true
								),
							array(
								'name' => 'owner_id',
								'type' => 'INT',
								'NULL' => false
								),
							array(
								'name' => 'department_id',
								'type' => 'INT'
								),
							array(
								'name' => 'hide_date',
								'type' => 'DATETIME',
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