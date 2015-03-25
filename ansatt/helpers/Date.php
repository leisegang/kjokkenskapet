<?php

class DateHelper {
	
	public function timestamp_to_nor($date_input, $type="short")
	{
		$months = array("", "januar", "februar", "mars", "april", "mai", "juni", "juli", "august", "september", "oktober", "november", "desember");
		
		$date_arr = explode(" ", $date_input);
		$date = explode("-", $date_arr[0]);
		$time = explode(":", $date_arr[1]);
		$year = $date[0];
		$month = $date[1];
		$day = $date[2];
		$hour = $time[0];
		$minute = $time[1];
		
		if ($type == "short")
			return "$day.$month.$year kl. $hour:$minute";
		elseif ($type == "long")
			return (int)$day . "." . $months[(int)$month] . " $year kl. $hour:$minute";
	}

	public function timestamp_to_nor_date($date_input)
	{	
		$date_arr = explode(" ", $date_input);
		$date = $date_arr[0] ? explode("-", $date_arr[0]) : $date_input;
		$year = $date[0];
		$month = $date[1];
		$day = $date[2];
		
		if ($day > 0 && $month > 0 && $year > 0)
			return "{$day}.{$month}.{$year}";
	}

		
	public function nor_to_en_date($date_input)
	{
		$date = explode(".", $date_input);
		$year = $date[2];
		$month = $date[1];
		$day = $date[0];
		
		if ($year && $month && $day)
			return $year . "-" . $month . "-" . $day;
	}
	
	public function str_to_time($input_time)
	{
		$time = strtotime($input_time);
		
		if ($time)
			return date("H:i", $time);
	}
}

?>