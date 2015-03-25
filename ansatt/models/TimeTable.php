<?php
require_once("Model.php");

class TimeTable extends Model {
	
	private $table = "timetables";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'title',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => true
								),
							array(
								'name' => 'filename',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => true
								),
							array(
								'name' => 'hide_date',
								'type' => 'DATETIME',
								'NULL' => true
								),
							array(
								'name' => 'department_id',
								'type' => 'INT'
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