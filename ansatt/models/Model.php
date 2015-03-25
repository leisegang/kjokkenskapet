<?php

class Model {
	
	var $table_prefix = 'ansatt_';
	private $validations;
	
	var $errors;
	
	function __construct($db=null, $params=null)
	{
		$this->db = $db;
		$this->id = $params['id'];
		$this->validations = array();
		
		if ($this->exists() && $params['install'] != 1)
		{
			$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}` WHERE id = {$this->id}";
			$this->fetch($sql);
		}
	}
	
	public function save()
	{
		// VALIDATE
		if ($this->errors)
			return;
	
		// SAVE TO DATABASE
		$id = $this->id;
		if ($id == null)
			$id = "NULL";
					
		if ($this->fields)
		{
			$number_types = array('INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'BIGINT', 'FLOAT', 'DOUBLE', 'DECIMAL');
			
			$sql = "INSERT INTO `{$this->table_prefix}{$this->table}` SET ";
			$sql .= "`id` = {$id}, ";
			
			foreach ($this->fields as $field)
			{
				if ($field['name'] === "locked")
					continue;
			
				// Wrap in '' if not number type
				if (in_array($field['type'], $number_types))
				{
					$value = $this->$field['name'] ? $this->$field['name'] : 0;
					$sql .= "`{$field['name']}` = {$value}, ";
				}
				else
				{
					$sql .= "`" . $field['name'] . "` = '{$this->$field['name']}', ";
				}
			}
			
			$sql .= "`created` = '" . date('Y-m-d H:i:s')  . "',
					 `updated` = '" . date('Y-m-d H:i:s')  . "'
					 ON DUPLICATE KEY UPDATE 
					 `id` = {$id}, ";
			
			foreach ($this->fields as $field)
			{
				if ($field['name'] === "locked")
					continue;
			
				// Number?
				if (in_array($field['type'], $number_types))
				{
					$value = $this->$field['name'] ? $this->$field['name'] : 0;
					$sql .= "`{$field['name']}` = {$value}, ";
				}
				// Not number
				else
				{
					$sql .= "`{$field['name']}` = '{$this->$field['name']}', ";
				}
			}
				
			$sql .= "`updated` = '" . date('Y-m-d H:i:s')  . "'";
			
			$this->db->query($sql) or $error = $this->db->error;
			
			if ($this->id == null)
				$this->id = $this->db->insert_id;
				
		}
		
		if ($error)
		{
			$this->errors = array("error_description" => $error);
			return $error;
		}
		else
			$this->errors = null;
	}
	
	public function db_up()
	{
		// CREATE TABLE IF NOT EXISTS
		$sql = "CREATE TABLE IF NOT EXISTS `{$this->table_prefix}{$this->table}`
			( id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
				
		foreach ($this->fields as $field)
		{					
			$sql .= $field['name'] . " " . $field['type'];
			
			if ($field['length'])
				$sql .= "(" . $field['length'] . ")";
			
			if ( ! $field['NULL'])
				$sql .= " NOT NULL";
						
			$sql .= ", ";
		}
				
		$sql .= "created DATETIME,
				 updated TIMESTAMP DEFAULT NOW()
				 )";
		$sql .= " engine=innodb default charset=utf8 collate=utf8_unicode_ci";
		
		$this->db->query($sql);
		
		
		$fields = array();
    	$columns = $this->db->query("SHOW columns FROM `{$this->table_prefix}{$this->table}`");
    	
    	while($col = $columns->fetch_assoc())
    	{
    		$fields[] = $col['Field'];
        }
		
		// CREATE FIELDS IF THEY DO NOT EXIST		
		foreach ($this->fields as $field)
		{					
			if ( ! in_array($field['name'], $fields))
			{
				$sql = "ALTER TABLE `{$this->table_prefix}{$this->table}` ADD ";
				$sql .= $field['name'] . " " . $field['type'];
				if ($field['length'])
					$sql .= "(" . $field['length'] . ")";
			
				if ( ! $field['NULL'])
					$sql .= " NOT NULL";
					
				$this->db->query($sql);
			}
		}
	}
	
	public function db_down()
	{
		$sql = "DROP TABLE `{$this->table_prefix}{$this->table}`";
		$this->db->query($sql);
	}
	
	public function get_all($params=null)
	{
		/*
		USAGE EXAMPLE:
		$this->get_all(array(	'where' => 'id=2',
								'order_by' => 'id',
								'order' => 'DESC'));
		*/
	
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`";
		
		if ($params['where'])
		{
			$sql .= " WHERE " . $params['where'];
		}
		
		if ($params['order_by'])
		{
			$sql .= " ORDER BY `" . $params['order_by'] . "`";
			
			if ($params['order'])
			{
				$sql .= " " . $params['order'];
			}
		}
		else
		{
			$sql .= " ORDER BY `created`";
		}
			
		if ($order == "desc")
			$sql .= " DESC";
		
		return $this->fetch_all($sql);
	}
	
	public function fetch($sql)
	{
		if ($result = $this->db->query($sql))
		{
			if ($result->num_rows)
			{
				foreach ($result->fetch_assoc() as $key => $value)
				{
					$this->$key = $value;
				}
			}
		}
	}
	
	public function fetch_all($sql) {
		$list = array();
		
		// Fetch all persons related to this object
		if ($result = $this->db->query($sql))
		{
			if ($result->num_rows)
			{
				while ($row = $result->fetch_assoc())
				{
					$class = get_class($this);
					$obj = new $class($this->db);
					foreach ($row as $key => $value)
					{
						$obj->$key = $value;
					}
					
					array_push($list, $obj);
				}
			}
		}
		
		return $list;
	}
	
	public function exists()
	{
		if ($this->id != null)
			return true;
		
		return false;
	}
	
	public function error_for($search_key)
	{
		// Responds with the text " error" for a specific fields
		// For use in forms to indicate error on a field
		//
		// USAGE:
		// <input type="text" name="firstname" class="<= error_for("firstname") \>" />
		
		if ($this->errors)
		{
			foreach ($this->errors as $key=>$value)
			{
				if (array_search($search_key, $value))
					return " error";
			}
		}
	}
	
	public function set_validates($key, $params, $key_name)
	{
		// 	Sets validation for a specific field
		//
		// 	USAGE:
		/* 	$object->set_validates("example_field", "required", "Error text")			For required fields
			$object->set_validates("example_field", array("min" => 7), "Error text")		For minimum number of digits
			$object->set_validates("example_field", array("max" => 7), "Error text")		For maximum number of digits
		*/
	
		$this->validations[] = array("key" => $key, "params" => $params, "key_name" => $key_name);
	}
	
	public function validate()
	{
		// Validates all parameteres set using $object->setValidates();
		$errors = array();
	
		foreach ($this->validations as $validation)
		{	
			// Required field is filled?
			if ($validation["params"] == "required" && $this->$validation["key"] == null)
			{
				$errors[] = array("error_for" => $validation["key"], "error_description" => "{$validation["key_name"]} m&aring; fylles ut.");
			}
			
			if ($validation["params"] == "numeric")
			{
				if (!is_numeric($this->$validation["key"]))
					$errors[] = array("error_for" => $validation["key"], "error_description" => "{$validation["key_name"]} m&aring; best&aring; av tall.");
			}
			
			// Array of parameters?
			if (is_array($validation["params"]))
			{
				// Numeric field long enough?
				if (isset($validation["params"]["min"])) {
					// Numeric value < (required digit num to the power of (min digit value - 1)) // example "4" => 1000
					if ($this->$validation["key"] < pow(10, ($validation["params"]["min"] - 1))) {
						$errors[] = array("error_for" => $validation["key"], "error_description" => "{$validation["key_name"]} m&aring; v&aelig;re minst {$validation["params"]["min"]} siffer langt.");
					}
				}
				
				// Numeric field too long?
				if (isset($validation["params"]["max"]))
				{
					// Numeric value > (required digit num to the power of max digit value) - 1) // example "4" => 9999
					if ($this->$validation["key"] > (pow(10, ($validation["params"]["max"])) - 1))
					{
						$errors[] = array("error_for" => $validation["key"], "error_description" => "{$validation["key_name"]} kan v&aelig;re maksimalt {$validation["params"]["max"]} siffer langt.");
					}
				}
				
				// Numeric field exactly # digits long?
				if (isset($validation["params"]["exactly"]))
				{
					// Numeric value exactly (required digit num to the power of max digit value) - 1) // example "4" => 9999
					if ($this->$validation["key"] < pow(10, ($validation["params"]["exactly"] - 1))
						|| $this->$validation["key"] > (pow(10, ($validation["params"]["exactly"])) - 1)) {
							$errors[] = array("error_for" => $validation["key"], "error_description" => "{$validation["key_name"]} m&aring; v&aelig;re akkurat {$validation["params"]["exactly"]} siffer langt.");
					}
				}
			}
			
		}
		
		
		if (count($errors) > 0)
			$this->errors = $errors;
		else
			$this->errors = null;
	}
	
	public function delete()
	{
		$sql = "DELETE FROM `{$this->table_prefix}{$this->table}` WHERE `id` = {$this->id}";
		$this->db->query($sql);
	}
	

	public function __get($key)
	{
		return $this->$key;
	}
	
	public function __set($key, $value)
	{
		$this->$key = $value;
	}
	
	public function __toString()
	{
		$str = 'Class: ' . get_class($this) . "<br />";
		$str .= 'Id: ' . $this->id;
		
		return $str;
	}
}

?>