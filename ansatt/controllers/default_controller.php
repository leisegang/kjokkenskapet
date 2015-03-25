<?php
require_once('../models/Settings.php');
require_once('../helpers/Security.php');
require_once('../helpers/DbConnect.php');
require_once('../helpers/Date.php');
require_once('../models/Message.php');
require_once('../models/TimeTable.php');
require_once('../models/Workspan.php');
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

// Check for selection based on department
$department_id = $_GET['avdeling'];
if ($department_id == "")
	$department_id = (int)$current_user->department_id;

if ($department_id != 0)	
	$department_SQL = " AND `department_id` IN ({$department_id}, 0) ";

// MESSAGES
$message = new Message($db);
$messages = $message->get_all(array(	'where' => "`hide_date` > '" . date('Y-m-d 23:59:59') . "'{$department_SQL}",
										'order_by' => 'created',
										'order' => 'DESC'));	

// TIME TABLES
$timetable = new TimeTable($db);
$timetables = $timetable->get_all(array( 'where' => "`hide_date` > '" . date('Y-m-d 23:59:59') . "'{$department_SQL}",
										 'order_by' => 'title'));

// WORKING HOURS
$workspan = new Workspan($db);

// DEPARTMENTS
$department = new Department($db);
$departments = $department->get_all(array('order_by' => 'title'));

$front_page = true;

// VIEWS
// Header
require_once("../views/default/header.php");

// Main page
require_once("../views/default/index.php");

// Messages
require_once("../views/messages/index.php");

// Work spans
require_once("../views/workspans/index.php");

// Time tables
require_once("../views/timetables/index.php");


// Footer
require_once("../views/default/footer.php");
