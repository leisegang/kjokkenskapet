<?php
ini_set('session.gc_maxlifetime', 60*20);
ini_set('session.gc_divisor', 1);
session_start();

require_once("Model.php");

class Settings extends Model {
	
	var $base_url;
	var $sha1_seed;
	var $local;
	
	private $table = "settings";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'food_cost',
								'type' => 'FLOAT',
								'NULL' => false
								),
							array(
								'name' => 'weekend_extra',
								'type' => 'FLOAT',
								'NULL' => false
								),
							array(
								'name' => 'evening_extra',
								'type' => 'FLOAT',
								'NULL' => false
								),
							array(
								'name' => 'night_extra',
								'type' => 'FLOAT',
								'NULL' => false
								),
							array(
								'name' => 'food_cost_limit',
								'type' => 'FLOAT',
								'NULL' => false
								)
						);
	
	public function __construct($db, $params=null)
	{
		parent::__construct($db, array('id' => 1, 'install' => $params['install']));
		
		$this->base_url = "/ansatt";
		$this->sha1_seed = "tkR6zptngPq8Tv";
		
		if ($_SERVER['SERVER_ADDR'] == '127.0.0.1')
		{
			$this->local =  true;
			$this->domain = 'kjokkenskapet.dev';
		}
		else
		{
			$this->local = false;
			$this->domain = 'kjokkenskapet.no';
		}
		
		date_default_timezone_set('Europe/Oslo');
		
		$this->singleton = true;
	}
	
	public function db_up()
	{
		parent::db_up();
		
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`";
		
		if ($result = $this->db->query($sql))
		{
			if ($result->num_rows <= 0)
			{
				$this->food_cost = 30;
				$this->weekend_extra = 19.88;
				$this->evening_extra = 10.47;
				$this->night_extra = 36.63;
				
				$this->save();
			}
		}
	}
	
	public function __get($key) {
		return $this->$key;
	}
	
}

?>