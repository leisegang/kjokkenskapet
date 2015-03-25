<?php
require_once("../models/Settings.php");
require_once("../helpers/Security.php");
require_once("../helpers/DbConnect.php");
require_once("../helpers/Date.php");
require_once('../models/Department.php');
require_once('../models/TimeTable.php');
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


if ($action == "new" || $action == "edit")
{
	if ($current_user->access_level < 1)
		$security->no_access();

	$timetable = new TimeTable($db, array('id' => $id));
	
	// CREATE NEW DEPARTMENT OBJECT
	$department = new Department($db);
	$departments = $department->get_all(array('order_by' => 'title'));
	
	if ($_POST['save'])
	{
		$timetable->title = $db->prepare($_POST['title']);
		$timetable->department_id = $db->prepare($_POST['department_id']);
		$timetable->hide_date = date("Y-m-d", strtotime($db->prepare($_POST['hide_date'])));
				
		$timetable->set_validates("title", "required", "Overskrift");
		$timetable->set_validates("hide_date", "required", "Utl&oslash;psdato");
		
		$timetable->validate();
		
		if ( ! $timetable->errors)
		{
			if ( ! $timetable->filename)
			{
				if (   ($_FILES["filename"]["type"] == "image/gif")
					|| ($_FILES["filename"]["type"] == "image/jpeg")
					|| ($_FILES["filename"]["type"] == "image/png")
					|| ($_FILES["filename"]["type"] == "image/pjpeg"))
				{
					if ($_FILES["filename"]["error"] > 0)
					{
						echo "Return Code: " . $_FILES["filename"]["error"] . "<br />";
					}
					else
					{
						if (file_exists("../public/images/" . $_FILES["filename"]["name"]))
						{
							$timetable->errors = array(array("error_description" => "Filen '" . $_FILES["filename"]["name"] . "' finnes allerede."));
						}
						else
						{
							move_uploaded_file($_FILES["filename"]["tmp_name"], "../public/images/" . $_FILES["filename"]["name"]);
							$timetable->filename = $_FILES["filename"]["name"];
						}
					}
				}
				else
				{
					$timetable->errors = array(array("error_description" => "Ugyldig filtype."));
				}
			}
			
			if ( ! $timetable->errors)
			{
				$timetable->save();
				$notice = "<p>Timelisten ble lagret.<p>";
			}
		}
	}	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/timetables/edit.php");		// View
	require_once("../views/default/footer.php");		// Footer
}


if ($action == "show_archive")
{
	if ($current_user->access_level < 1)
		$security->no_access();
			
	$timetable = new TimeTable($db);
	$timetables = $timetable->get_all(array('where' => "`hide_date` < '" . date("Y-m-d 23:59:59") . "'",
											'order_by' => 'created',
											'order' => 'DESC'));
	
	$archive = true;
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/timetables/index.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

if ($action == "archive")
{
	if ($current_user->access_level < 1)
		$security->no_access();
		
	$timetable = new TimeTable($db, array("id" => $id));
	$timetable->hide_date = date('Y-m-d');
	$timetable->save();
	
	if (isset($_GET['return_url']))
	{
		$url = urldecode($_GET['return_url']);
		header('Location: ' . $url);
		exit;
	}
	else
	{
		echo "Melding slettet.";
	}
}

// DELETE IMAGE
if ($action == "delete_image")
{	
	if ($current_user->access_level < 1)
		$security->no_access();
	
	$timetable = new TimeTable($db, array("id" => $id));
	
	// Delete file and link to file
	unlink('../public/images/' . $timetable->filename);
	$timetable->filename = null;
	
	$timetable->save();
		
	// Redirect back
	if (isset($_GET['return_url']))
	{
		$url = urldecode($_GET['return_url']);
		header('Location: ' . $url);
		exit;
	}
	else
	{
		echo "Bilde slettet.";
	}
}


// DELETE
if ($action == "delete")
{	
	if ($current_user->access_level < 1)
		$security->no_access();
	
	$timetable = new TimeTable($db, array("id" => $id));
	
	// Delete file and link to file
	unlink('../public/images/' . $timetable->filename);
	$timetable->delete();
	
	// Redirect back
	if (isset($_GET['return_url']))
	{
		$url = urldecode($_GET['return_url']);
		header('Location: ' . $url);
		exit;
	}
	else
	{
		echo "Vaktliste slettet.";
	}
}

?>