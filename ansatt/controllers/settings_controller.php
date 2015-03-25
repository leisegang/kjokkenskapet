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
	if ($_POST['save'])
	{
		$settings->food_cost = str_replace(",", ".", $db->prepare($_POST['food_cost']));
		$settings->weekend_extra = str_replace(",", ".", $db->prepare($_POST['weekend_extra']));
		$settings->evening_extra = str_replace(",", ".", $db->prepare($_POST['evening_extra']));
		$settings->night_extra = str_replace(",", ".", $db->prepare($_POST['night_extra']));
		$settings->food_cost_limit = str_replace(",", ".", $db->prepare($_POST['food_cost_limit']));
		
		if ( ! $settings->errors)
		{
			$settings->save();
			
			if ( ! $settings->errors)
			{
				$notice = "<p>Innstillingene ble lagret.<p>";
			}
		}
	}	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/settings/edit.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

?>