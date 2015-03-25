<?php
require_once("Model.php");
require_once("Settings.php");
require_once("User.php");

class Workspan extends Model {
	
	private $table = "workspans";
	
	// Custom fields
	private $fields = array(
							array(
								'name' => 'employee_id',
								'type' => 'INT'
								),
							array(
								'name' => 'start_time',
								'type' => 'DATETIME'
								),
							array(
								'name' => 'end_time',
								'type' => 'DATETIME'
								),
							array(
								'name' => 'sick',
								'type' => 'TINYINT'
								),
							array(
								'name' => 'department_id',
								'type' => 'INT'
								)
						);
	
	public function __construct($db, $params=null)
	{	
		parent::__construct($db, $params);
		$this->settings = new Settings($db);
	}
	
	public function get_all_by_date_and_id($date, $id)
	{
		$date = strtotime($date);
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}` WHERE employee_id = {$id} AND start_time >= '" . date("Y-m-d 00:00:00", $date) . "' AND end_time <= '" . date("Y-m-d 23:59:59", $date) . "' ORDER BY start_time";
		
		return $this->fetch_all($sql);
	}
	
	public function get_sum_by_date_and_id($date, $id, $type=null)
	{		
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}` WHERE `employee_id` = {$id}";
		
		switch ($type)
		{
			case "month":
				$sql .= " AND start_time >= '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m", strtotime($date)), 1, date("Y", strtotime($date)))) . "' AND end_time < '" . date("Y-m-d 23:59:59", mktime(0, 0, 0, date("m", strtotime($date)), date("t", strtotime($date)), date("Y", strtotime($date)))) . "'";
				break;
			
			default:
				$end_time = strtotime($date);
				$end_time = mktime(date("H", $end_time), date("i", $end_time), date("s", $end_time), date("n", $end_time), date("j", $end_time) + 1, date("Y", $end_time));
				$sql .= " AND start_time >= '{$date}' AND end_time < '" . date("Y-m-d", $end_time) . "'";
				break;
		}
		 
		$sum = $this->fetch_sum($sql);		
		
		return number_format($sum / 3600, 2);
	}
	
	public function get_sum_by_timespan_and_id($start_date, $end_date, $id=null, $department=null, $sick_leave=null)
	{
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`";
		
		if ($id)
			$sql .= " WHERE `employee_id` = {$id} AND";
		else
			$sql .= " WHERE";
		
		$sql .= " `start_time` >= '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m", strtotime($start_date)), date("d", strtotime($start_date)), date("Y", strtotime($start_date)))) . "'";
		$sql .= " AND `end_time` < '" . date("Y-m-d 23:59:59", mktime(0, 0, 0, date("m", strtotime($end_date)), date("d", strtotime($end_date)), date("Y", strtotime($end_date)))) . "'";
		
		$sql .= " AND `sick` = " . ($sick_leave ? 1 : 0);
		
		$sql .= $department > 0 ? " AND `department_id` = {$department}" : "";
		
		return $this->fetch_sum($sql) / 3600;
	}
	
	public function get_extra_by_timespan_and_id($start_date, $end_date, $id=null, $department=null, $sick_leave=null)
	{
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`";
		
		if ($id)
			$sql .= " WHERE `employee_id` = {$id} AND";
		else
			$sql .= " WHERE";
		
		$sql .= " `start_time` >= '" . date("Y-m-d 00:00:00", strtotime($start_date)) . "'";
		$sql .= " AND `end_time` < '" . date("Y-m-d 23:59:59", strtotime($end_date)) . "'";
				
		$sql .= $department > 0 ? " AND `department_id` = {$department}" : "";
		
		$user = new User($this->db, array('id' => $id));
		
		if ($result = $this->db->query($sql))
		{
			if ($result->num_rows)
			{
				while ($row = $result->fetch_assoc())
				{
					if ( ! $user->no_extra)		// User should have extras added to hourly salary
					{
						// Night? (Start time earlier than 06:00.)
						if (date("G", strtotime($row['start_time'])) < 6)
						{
							// End time after 06:00 -> set to 06:00
							if (date("Gi", strtotime($row['end_time'])) > 600)
							{
								$end_time = mktime(6, 0, 0, date("n", strtotime($row['end_time'])), date("j", strtotime($row['end_time'])), date("Y", strtotime($row['end_time'])));
							}
							// End time before 06:00 -> set to registered end time
							else
							{
								$end_time = strtotime($row['end_time']);
							}
					
							$sum += ((($end_time - strtotime($row['start_time'])) / 3600) * $this->settings->night_extra);
						}
					
						// Saturday?
						if (date("w", strtotime($row['start_time'])) == 6)
						{					
							// End time after 14:00?
							if (date("Gi", strtotime($row['end_time'])) > 1400)
							{
								// Start time earlier than 14:00 -> set to 14:00
								if (date("Gi", strtotime($row['start_time'])) < 1400)
								{	
									$start_time = mktime(14, 0, 0, date("n", strtotime($row['start_time'])), date("j", strtotime($row['start_time'])), date("Y", strtotime($row['start_time'])));
								}
								// Start time after 14:00 -> set to registered start time
								else
								{
									$start_time = strtotime($row['start_time']);
								}
							
								$sum += (((strtotime($row['end_time']) - $start_time) / 3600) * $this->settings->weekend_extra);
							}
						}				
						// Sunday?
						else if (date("w", strtotime($row['start_time'])) == 0)
						{
							// Start time earlier than 06:00 -> set to 06:00
							if (date("G", strtotime($row['start_time'])) < 6)
							{
								$start_time = mktime(6, 0, 0, date("n", strtotime($row['start_time'])), date("j", strtotime($row['start_time'])), date("Y", strtotime($row['start_time'])));
							}
							// Start time later than 06:00 -> set to registered start time
							else
							{
								$start_time = strtotime($row['start_time']);
							}
						
							$sum += (((strtotime($row['end_time']) - $start_time) / 3600) * $this->settings->weekend_extra);
						}
						// Weekday after 21:00?
						else if (date("Gi", strtotime($row['end_time'])) >= 2100)
						{
							// Start time earlier than 21:00 -> set to 21:00
							if (date("Gi", strtotime($row['start_time'])) < 2100)
							{
								$start_time = mktime(21, 0, 0, date("n", strtotime($row['start_time'])), date("j", strtotime($row['start_time'])), date("Y", strtotime($row['start_time'])));
							}
							// Start time later than 21:00 -> set to registered start time
							else
							{
								$start_time = strtotime($row['start_time']);
							}
	
							$sum += (((strtotime($row['end_time']) - $start_time) / 3600) * $this->settings->weekend_extra);
						}
					}
					
					// Holiday?
					$sql = "SELECT date FROM {$this->table_prefix}holidays WHERE `date` = '" . date("Y-m-d", strtotime($start_date)) . "'";
					if ($h_result = $this->db->query($sql))
					{
						if ($h_result->num_rows)
						{
							while ($h_result->fetch_assoc())
							{
								$sum += (((strtotime($row['end_time']) - strtotime($row['start_time'])) / 3600) * $user->salary);	
							}
						}
					}
				}
			}
		}
				
		// Reduce extra if percentage is set
		if ($sum > 0 && $user->percentage < 100)
			$sum = $sum * $user->percentage / 100;
		
		return $sum;
	}
	
	public function get_food_cost_by_timespan_and_id($start_date, $end_date, $id=null, $department=null)
	{
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`";
		
		if ($id)
			$sql .= " WHERE `employee_id` = {$id} AND";
		else
			$sql .= " WHERE";
		
		$sql .= " `start_time` >= '" . date("Y-m-d 00:00:00", mktime(0, 0, 0, date("n", strtotime($start_date)), date("j", strtotime($start_date)), date("Y", strtotime($start_date)))) . "'";
		$sql .= " AND `end_time` < '" . date("Y-m-d 23:59:59", mktime(0, 0, 0, date("n", strtotime($end_date)), date("j", strtotime($end_date)), date("Y", strtotime($end_date)))) . "'";
		
		$sql .= $department > 0 ? " AND `department_id` = {$department}" : "";
		
		$sum = 0;
		
		if ($result = $this->db->query($sql))
		{
			if ($result->num_rows)
			{
				while ($row = $result->fetch_assoc())
				{
					if ((((strtotime($row['end_time']) - strtotime($row['start_time'])) / 3600) >= $this->settings->food_cost_limit) && ($row['sick'] == 0))
					{
						$sum += $this->settings->food_cost;
					}
				}
			}
		}
		
		return $sum;
	}
	
	public function get_salary_by_timespan_and_id($start_date, $end_date, $id=null, $department=null, $type=null)
	{
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}`";
		
		if ($id)
			$sql .= " WHERE `employee_id` = {$id} AND";
		else
			$sql .= " WHERE";
		
		$sql .= " `start_time` >= '" . date("Y-m-d 00:00:00", strtotime($start_date)) . "'";
		$sql .= " AND `end_time` < '" . date("Y-m-d 23:59:59", strtotime($end_date)) . "'";
		
		$sql .= $department > 0 ? " AND `department_id` = {$department}" : "";
		
		$sum = 0;
		$user = new User($this->db, array('id' => $id));
		
		// Static salary -> calculate salary based on number of day selected
		if ($user->static_salary)
		{
			if ($type == 'month')
			{
				$sum = $user->static_salary;
			}
			else
			{			
				$days_in_month = date("t", strtotime($start_date));
				$start_time = mktime(0, 0, 0, date("n", strtotime($start_date)), date("j", strtotime($start_date)), date("Y", strtotime($start_date)));
				$end_time = mktime(0, 0, 0, date("n", strtotime($end_date)), date("j", strtotime($end_date))+1, date("Y", strtotime($end_date)));
				$number_of_days_spanned = (int)(($end_time - $start_time) / (60 * 60 * 24));
				
				$sum = ($number_of_days_spanned / $days_in_month) * $user->static_salary;
			}
		}
		// Hourly salary -> calculate based on number of hours worked
		else if ($result = $this->db->query($sql))
		{
			if ($result->num_rows)
			{
				while ($row = $result->fetch_assoc())
				{
					if ($user->salary)
					{
						$sum += (((strtotime($row['end_time']) - strtotime($row['start_time'])) / 3600) * $user->salary);
					}
					
				}
			}
		}
		
		// Reduce extra if percentage is set
		if ($sum > 0 && $user->percentage < 100)
			$sum = $sum * $user->percentage / 100;
		
		return $sum;
	}
	
	public function get_all_by_employee_id($id)
	{
		$sql = "SELECT * FROM `{$this->table_prefix}{$this->table}` WHERE `employee_id` = {$id}";
		
		$list = array();
		
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
	
	public function fetch_sum($sql)
	{
		if ($result = $this->db->query($sql))
		{
			if ($result->num_rows)
			{
				while ($row = $result->fetch_assoc())
				{
					$sum += (strtotime($row['end_time']) - strtotime($row['start_time']));
				}
			}
		}
		
		return $sum;
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