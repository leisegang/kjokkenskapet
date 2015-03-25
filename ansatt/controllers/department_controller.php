<?php
require_once("../models/Settings.php");
require_once("../helpers/Security.php");
require_once("../helpers/DbConnect.php");
require_once("../helpers/Date.php");
require_once('../models/User.php');
require_once('../models/Department.php');

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

if ($current_user->access_level < 3)
	$security->no_access();

if ($action == "new" || $action == "edit")
{
	$department = new Department($db, array('id' => $id));
	
	if ($_POST['save'])
	{	
		$department->title = $db->prepare($_POST['title']);
		$department->short_title = $db->prepare($_POST['short_title']);
		
		$department->set_validates('title', 'required', 'Navn p&aring; avdeling');
		$department->validate();
		
		if ( ! $department->errors)
		{
			$department->save();
			
			if ( ! $department->errors)
			{
				$notice = "<p>Avdelingen ble lagret.<p>";
			}
		}
	}	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/departments/edit.php");		// View
	require_once("../views/default/footer.php");		// Footer
}


if ($action == "index")
{
	$department = new Department($db);
	$departments = $department->get_all(array('order_by' => 'title'));
	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/departments/index.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

?>