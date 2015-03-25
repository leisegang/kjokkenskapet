<?php
require_once("Model.php");

class User extends Model {
	
	private $table = "users";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'name',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => false
								),
							array(
								'name' => 'password',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => false
							),
							array(
								'name' => 'email',
								'type' => 'varchar',
								'length' => 100,
								'NULL' => false
								),
							array(
								'name' => 'salary',
								'type' => 'float',
								'NULL' => false
								),
							array(
								'name' => 'static_salary',
								'type' => 'float',
								'NULL' => false
								),
							array(
								'name' => 'birthdate',
								'type' => 'DATE',
								'NULL' => true
								),
							array(
								'name' => 'address',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => true
								),
							array(
								'name' => 'zipcode',
								'type' => 'INT',
								'length' => 8,
								'NULL' => true
								),
							array(
								'name' => 'city',
								'type' => 'varchar',
								'length' => 255,
								'NULL' => true
								),
							array(
								'name' => 'department_id',
								'type' => 'INT',
								'NULL' => false
								),
							array(
								'name' => 'access_level',
								'type' => 'INT',
								'length' => 1,
								'NULL' => false
								),
							array(
								'name' => 'no_extra',
								'type' => 'INT',
								'length' => 1,
								'NULL' => false
								),
							array(
								'name' => 'percentage',
								'type' => 'INT',
								'length' => 1,
								'default' => 100
								),
							array(
								'name' => 'locked',
								'type' => 'INT',
								'length' => 1,
								'default' => 0
								),
							array(
								'name' => 'last_login',
								'type' => 'DATETIME',
								'NULL' => true
								)
						);
	
	public function __construct($db, $params=null)
	{	
		parent::__construct($db, $params);
		
		if ($params['hash'])
		{
			$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`
					WHERE `password` = '" . $params['hash'] . "'";
			
			$this->fetch($sql);
		}
		
		if ($params['username'] && $params['password'])
		{
			$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`
					WHERE `email` = '" . $params['username'] . "' 
					AND `password` = '" . sha1($params['username'] . $params['sha1_seed'] . $params['password']) . "'";
			
			$this->fetch($sql);
			
			if ( ! $this->exists())
			{
				$this->errors = array(array("error_description" => "Feil brukernavn eller passord."));
			}
		}
		
		if ($params['email'])
		{
			$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`
					WHERE `email` = '" . $params['email'] . "'";
			
			$this->fetch($sql);
			
			if ( ! $this->exists())
			{
				$this->errors = array(array("error_description" => "Fant ingen brukere med den oppgitte e-postadressen."));
			}
		}
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