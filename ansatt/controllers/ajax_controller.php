<?php
require_once("../models/Settings.php");
require_once("../helpers/Security.php");
require_once("../helpers/DbConnect.php");
require_once("../helpers/Date.php");
require_once('../models/Workspan.php');
require_once('../models/User.php');

$db = new DbConnect();
$db->show_errors();
$settings = new Settings($db);
$security = new Security($db);
$date_helper = new DateHelper();
$current_user = $security->current_user;

$security->check();

// Get methods
$action = $_GET['action'] ? $_GET['action'] : $_POST['action'];

if ($action == "get_workspans_for_date_and_id")
{
	$workspan = new Workspan($db);
	
	$date = $db->prepare($_GET['date']);
	$id = $db->prepare($_GET['id']);
	
	if ($current_user->id == $id || $current_user->access_level >= 3)
	{
		$workspans = $workspan->get_all_by_date_and_id($date, $id);
		
		$list = array();
		if ($workspans)
		{
			foreach ($workspans as $span)
			{
				$obj = array(	"id" => $span->id,
								"employee_id" => $span->employee_id,
								"start_time" => $span->start_time,
								"end_time" => $span->end_time,
								"sick" => $span->sick,
								"sum" => number_format(((strtotime($span->end_time) - strtotime($span->start_time)) / 3600), 2),
								"created" => $span->created,
								"updated" => $span->updated);
				array_push($list, $obj);
			}
		}
			
		if ($list)
			$response = array(	"responseCode" => "OK",
								"response" => $list);
		else
			$response = array(	"responseCode" => "ERROR",
								"response" => "Det er ikke registrert noen timer p&aring; denne datoen.");
	}
	else
	{
		$response = array(	"responseCode" => "ERROR",
							"response" => "Ugyldig tilgang.");
	}
	
	echo json_encode($response);
}
else if ($action == "save_workspan")
{
	$workspan = new Workspan($db);
	
	$employee_id = $db->prepare($_POST['id']);
	$start_time = $db->prepare($_POST['start_time']);
	$end_time = $db->prepare($_POST['end_time']);
	$sick = (isset($_POST['sick']) && $_POST['sick'] == "1") ? 1 : 0;
	$department_id = $db->prepare($_POST['department_id']);
	$date = $db->prepare($_POST['date']);
	
	
	if (($current_user->id == $employee_id && date("Y-m-d", strtotime($date)) == date("Y-m-d", time())) || $current_user->access_level >= 3)
	{
		$workspan->employee_id = $employee_id;
		$workspan->start_time = $date . " " . $start_time;
		$workspan->end_time = $date . " " . $end_time;
		$workspan->sick = $sick;
		$workspan->department_id = $department_id;
		
		$workspan->save();
		
		$response = array(	"responseCode" => "OK",
							"sum" => $workspan->get_sum_by_date_and_id($date, $employee_id),
							"month_sum" => $workspan->get_sum_by_date_and_id($date, $employee_id, "month"));
	}
	else
	{
		$response = array(	"responseCode" => "ERROR",
							"response" => "Ugyldig tilgang.");
	}
	
	echo json_encode($response);
}
else if ($action == "delete_workspan")
{
	$id = $db->prepare($_POST['id']);
	$date = $db->prepare($_POST['date']);
	
	$workspan = new Workspan($db, array('id' => $db->prepare($id)));
	$employee_id = $workspan->employee_id;
	
	if ($current_user->id == $employee_id || $current_user->access_level >= 3)
	{	
		$workspan->delete();
		$workspan = new Workspan($db);
			
		$response = array(	"responseCode" => "OK",
							"sum" => $workspan->get_sum_by_date_and_id($date, $employee_id),
							"month_sum" => $workspan->get_sum_by_date_and_id($date, $employee_id, "month"));
	}
	else
	{
		$response = array(	"responseCode" => "ERROR",
							"response" => "Ugyldig tilgang.");
	}
	
	echo json_encode($response);
}
else
{
	echo "Unknown action.";
}