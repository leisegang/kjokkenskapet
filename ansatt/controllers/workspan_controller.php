<?php
require_once("../models/Settings.php");
require_once("../helpers/Security.php");
require_once("../helpers/DbConnect.php");
require_once("../helpers/Date.php");
require_once('../models/Department.php');
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
$id = $db->prepare($_GET['id']);
$action = $_GET['action'];

if ($action == "show")
{
	if ($current_user->access_level < 2)
		$security->no_access();
	
	$workspan = new Workspan($db);
	
	$date = $db->prepare($_GET['date']);
	
	$list = $workspan->get_all_by_date_and_id($date, $id);
	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/workspans/show.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

if ($action == "show_report")
{
	if ($current_user->access_level < 2)
		$security->no_access();
	
	// WORKSPAN
	$workspan = new Workspan($db);
	
	// USERS
	$user_class = new User($db);
	$params = array("order_by" => "name");
	$dep = isset($_GET['avdeling']) ? $db->prepare($_GET['avdeling']) : 0;
	//if ($dep > 0)
	//	$params['where'] = "`department_id` = {$dep} OR `static_salary` > 0";
	if ($current_user->access_level < 3)
		$params['where'] = "`department_id` = " . $current_user->department_id;
		
	$users = $user_class->get_all($params);
	
	// DEPARTMENTS
	$department = new Department($db);
	$departments = $department->get_all(array('order_by' => 'title'));
		
	if (isset($_GET['sluttdato']))
	{
		$person = $db->prepare($_GET['ansatt']);
		
		if (isset($person))
			$user = new User($db, array('id' => $person));
		
		$report = true;
	}
		
			
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/workspans/report.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

if ($action == "new" || $action == "edit")
{
	$workspan = new Workspan($db, array('id' => $id));
	
	$user = new User($db, array('id' => $db->prepare($_GET['user'])));
	
	$department = new Department($db);
	$departments = $department->get_all(array('order_by' => 'title'));
	
	// SET VALUES
	$employee_id = $workspan->employee_id ? $workspan->employee_id : $db->prepare($_GET['user']);
	$date = $workspan->start_time ? date("d.m.Y", strtotime($workspan->start_time)) : $_GET['date'];
	$start_time = $workspan->start_time;
	$end_time = $workspan->end_time;
	
	if ( ! $workspan->department_id)
		$workspan->department_id = $user->department_id;
	
	if (isset($_POST['save']))
	{
		$date = $db->prepare($_POST['date']);
		$start_time = $db->prepare($_POST['start_time']);
		$end_time = $db->prepare($_POST['end_time']);
		
		$workspan->sick = $_POST['sick'] == 1 ? 1 : 0;
		$workspan->department_id = $db->prepare($_POST['department']);
						
		// ERROR VALIDATION
		$errors = array();
		
		if ( ! $date)
			array_push($errors, array('error_description' => "Ugyldig dato."));
				
		if ( ! $start_time)
			array_push($errors, array('error_description' => "Ugyldig starttidspunkt."));
		
		if ( ! $end_time)
			array_push($errors, array('error_description' => "Ugyldig sluttidspunkt."));
		
		if ($start_time && $end_time)
			if (strtotime($end_time) < strtotime($start_time))
				array_push($errors, array('error_description' => "Sluttidspunkt kan ikke v&aelig;re tidligere enn starttidspunkt."));
		
		if ( ! $errors)
		{	
			$workspan->start_time = date("Y-m-d", strtotime($date)) . " " . date("H:i", strtotime($start_time));
			$workspan->end_time = date("Y-m-d", strtotime($date)) . " " . date("H:i", strtotime($end_time));
			$workspan->employee_id = $employee_id;
			$workspan->save();
			
			$notice = "Timene er lagret.";
		}
						
	}
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/workspans/edit.php");			// View
	require_once("../views/default/footer.php");		// Footer
}


// DELETE
if ($action == "delete")
{	
	if ($current_user->access_level < 2)
		$security->no_access();
	
	$workspan = new Workspan($db, array("id" => $id));
	$workspan->delete();
	
	if (isset($_GET['return_url']))
	{
		$url = urldecode($_GET['return_url']);
		header('Location: ' . $url);
		exit;
	}
	else
	{
		echo "Timene er slettet.";
	}
}

?>