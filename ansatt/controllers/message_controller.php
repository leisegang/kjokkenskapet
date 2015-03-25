<?php
require_once("../models/Settings.php");
require_once("../helpers/Security.php");
require_once("../helpers/DbConnect.php");
require_once("../helpers/Date.php");
require_once('../models/Message.php');
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


if ($action == "new" || $action == "edit")
{
	if ($current_user->access_level < 1)
		$security->no_access();

	$department = new Department($db);
	$departments = $department->get_all(array('order_by' => 'title'));

	$message = new Message($db, array('id' => $id));

	if ($_POST['save'] || $_POST['upload'])
	{
		$message->title = $db->prepare($_POST['title']);
		$message->content = $db->prepare($_POST['content']);
		$message->department_id = $db->prepare($_POST['department_id']);
		$message->hide_date = $date_helper->nor_to_en_date($db->prepare($_POST['hide_date']));
		if ( ! $message->exists())
			$message->owner_id = $current_user->id;
		
		$message->set_validates("title", "required", "Overskrift");
		$message->set_validates("content", "required", "Tekst");
		$message->set_validates("hide_date", "required", "Utl&oslash;psdato");
				
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
						$message->errors = array(array("error_description" => "Filen '" . $_FILES["filename"]["name"] . "' finnes allerede."));
					}
					else
					{
						move_uploaded_file($_FILES["filename"]["tmp_name"], "../public/images/" . $_FILES["filename"]["name"]);
						$filename = $_FILES["filename"]["name"];
					}
				}
			}
			else
			{
				$message->errors = array(array("error_description" => "Ugyldig filtype."));
			}
		
		if ($_POST['save'])
		{
			$message->validate();
			
			if ( ! $message->errors)
			{
				$message->save();
				
				if ( ! $message->errors)
				{
					$notice = "<p>Meldingen ble lagret.<p>";
				}
			}
		}
		
		$message->content = $_POST['content']; // <- Hack to remove \r\n characters inserted before DB insert
	}	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/messages/edit.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

if ($action == "show_archive")
{
	if ($current_user->access_level < 1)
		$security->no_access();
	
	$message = new Message($db);
	$messages = $message->get_all(array(	'where' => "`hide_date` < '" . date("Y-m-d 23:59:59") . "'",
											'order_by' => 'created',
											'order' => 'DESC'));
	
	$archive = true;
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/messages/index.php");		// View
	require_once("../views/default/footer.php");		// Footer
}

if ($action == "archive")
{
	if ($current_user->access_level < 1)
		$security->no_access();
		
	$message = new Message($db, array("id" => $id));
	$message->hide_date = date('Y-m-d');
	$message->save();
	
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

// DELETE
if ($action == "delete")
{	
	if ($current_user->access_level < 1)
		$security->no_access();
	
	$message = new Message($db, array("id" => $id));
	$message->delete();
	
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

?>