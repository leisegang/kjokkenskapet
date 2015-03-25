<?php
require_once("../models/Settings.php");
require_once("Security.php");
require_once("DbConnect.php");
require_once("Date.php");
require_once('../models/Department.php');
require_once('../models/Workspan.php');
require_once('../models/User.php');

require('class.php-excel-custom.php');

$db = new DbConnect();
$db->show_errors();
$settings = new Settings($db);
$security = new Security($db);
$date_helper = new DateHelper();
$current_user = $security->current_user;

$security->check();

if ($current_user->access_level < 3)
	$security->no_access();

// Get methods
$id = $db->prepare($_GET['id']);
$action = $_GET['action'];


// GET INPUT
$department = $db->prepare($_GET['avdeling']);
$start_date = $db->prepare($_GET['startdato']);
$end_date = $db->prepare($_GET['sluttdato']);

// WORKSPAN CONTROLLER
$workspan = new Workspan($db);

// USERS
$user_class = new User($db);
$params = array('order_by' => 'name');
$dep = isset($_GET['avdeling']) ? $db->prepare($_GET['avdeling']) : 0;
//if ($dep > 0)
//	$params['where'] = "department_id = {$dep}";

$users = $user_class->get_all($params);


// DEPARTMENT
$department_controller = new Department($db, array('id' => $department));

// START XML OUTPUT
$data = array(
        1 => array ('Navn', utf8_encode('F&oslash;dselsdato'), 'Adresse', 'Postnr./-sted', 'E-post', 'Arbeidstimer', utf8_encode('Sykefrav&aelig;r'), 'Tillegg', utf8_encode('Timel&oslash;nn'), utf8_encode('Totall&oslash;nn'), 'Kosttrekk')
        );

$i = 2;
foreach ($users as $user)
{
	$hours = $workspan->get_sum_by_timespan_and_id(
					$start_date,
					$end_date,
					$user->id,
					$department);
	$sick_hours = $workspan->get_sum_by_timespan_and_id(
					$start_date,
					$end_date,
					$user->id,
					$department,
					"sick");
	$extra = $workspan->get_extra_by_timespan_and_id(
					$start_date,
					$end_date,
					$user->id,
					$department);
	$salary = $workspan->get_salary_by_timespan_and_id(
					$start_date,
					$end_date,
					$user->id,
					$department,
					"month");
	$food_cost = $workspan->get_food_cost_by_timespan_and_id(
					$start_date,
					$end_date,
					$user->id,
					$department);
	$sum_hours = $hours + $sick_hours;
						
	// Skip this user if no hours are present and this it not their home group
	if ($department > 0 && ($department != $_user->department_id))
	{
		if ($hours + $sick_hours == 0)
			continue;
		else
		{
			if ($_user->static_salary > 0)
			{
				$salary = 0;
				$food_cost = 0;
			}
		}
	}
	
	$salary = $user->static_salary ? $salary : $salary + $extra;
		
	$total_hours += $hours;
	$total_sick_hours += $sick_hours;
	$total_extra += $extra;
	$total_food_cost += $food_cost;
	$total_salary += $salary;
	$total_sum_hours += $sum_hours;
	
	$salary = $user->static_salary ? $user->static_salary * $user->percentage / 100 : "=(((RC[-4]+RC[-3])*RC[-1])*{$user->percentage}/100)+RC[-2]";
		
	$data[] = array(	$user->name,
						$date_helper->timestamp_to_nor_date($user->birthdate),
						$user->address,
						($user->zipcode > 0 ? $user->zipcode . ' ' : '') . $user->city,
						$user->email,
						$hours,
						$sick_hours,
						$extra ? $extra : 0,
						$user->salary,
						$salary,
						$food_cost
					);
	$i++;
}
					
// Add totals

$data[] = array(	'Totalt',
					'',
					'',
					'',
					'',
					$total_hours,
					$total_sick_hours,
					$total_extra,
					'',
					$total_salary,
					$total_food_cost
				);

// generate file (constructor parameters are optional)
$xls = new Excel_XML('UTF-8', true, 'Kjokkenskapet');
$xls->addArray($data);

$myFile = $_SERVER['DOCUMENT_ROOT'] . "/ansatt/public/GfC2pbG2eKbtVE/Timeliste.xls";
$fh = fopen($myFile, 'w') or die("Kan ikke &aring;pne fil.");
fwrite($fh, $xls->generateXML('Timeliste'));
fclose($fh);

header('Content-type: application/xls');
header('Content-Disposition: attachment; filename="Timeliste' . ($department_controller->title ? ' ' .$department_controller->title : '') . '.xls"');
readfile($myFile);

//$url = $myFile;
//header('Location: ' . $url);

?>