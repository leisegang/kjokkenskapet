<?php
require_once("../models/Settings.php");
require_once("../helpers/Security.php");
require_once("../helpers/DbConnect.php");
require_once("../helpers/Date.php");
require_once('../models/User.php');
require_once('../models/Holiday.php');

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
	$holiday = new Holiday($db, array('id' => $id));
	
	if ($_POST['save'])
	{	
		$holiday->date = $db->prepare($_POST['date']);
		$holiday->name = $db->prepare($_POST['name']);
		
		$holiday->set_validates('date', 'required', 'Dato');
		$holiday->validate();
		
		if ( ! $holiday->errors)
		{
			$holiday->date = date("Y-m-d", strtotime($db->prepare($_POST['date'])));
			$holiday->save();
			
			if ( ! $holiday->errors)
			{
				$notice = '<p>Helligdagen ble lagret. <a href="' . $settings->base_url . '/innstillinger/helligdager/ny" class="margin-left-10">Legg til ny &rarr;</a><p>';
			}
		}
	}	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/holidays/edit.php");			// View
	require_once("../views/default/footer.php");		// Footer
}


if ($action == "index")
{
	$holiday = new Holiday($db);
	$holidays = $holiday->get_all(array('order_by' => 'date'));
	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/holidays/index.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

// DELETE
if ($action == "delete")
{	
	if ($current_user->access_level < 2)
		$security->no_access();
	
	$holiday = new Holiday($db, array("id" => $id));
	$holiday->delete();
	
	if (isset($_GET['return_url']))
	{
		$url = urldecode($_GET['return_url']);
		header('Location: ' . $url);
		exit;
	}
	else
	{
		echo "Helligdag slettet.";
	}
}

?>