<?php
require_once("../helpers/DbConnect.php");
require_once("../helpers/Security.php");
require_once("../helpers/Date.php");
require_once("../models/Settings.php");
require_once("../models/User.php");
require_once("../models/Department.php");
require_once("../helpers/PHPMailerKjokkenskapet.php");
require_once("../helpers/PHPMailerLocal.php");

$db = new DbConnect();
$db->show_errors();
$date_helper = new DateHelper();
$settings = new Settings($db);
$security = new Security($db);
$current_user = $security->current_user;

$security->check();

if ($current_user->access_level < 3)
	$security->no_access();

// ACTION AND ID
$id = $db->prepare($_GET['id']);
$action = $db->prepare($_GET['action']);

// PATH: /users/1/edit || /users/new
if ($action == "edit" || $action == "new")
{
	// CREATE USER OBJECT
	$user = new User($db, array("id" => $id));
	
	// CREATE NEW DEPARTMENT OBJECT
	$department = new Department($db);
	$departments = $department->get_all(array('order_by' => 'title'));
	
	// SAVE INFORMATION
	if (isset($_POST['save'])) {
		$user->id = $db->prepare($_POST['id']);
		$user->name = $db->prepare($_POST['name']);
		$user->email = strtolower($db->prepare($_POST['email']));
		$user->birthdate = $_POST['birthdate'] ? date("Y-m-d", strtotime($db->prepare($_POST['birthdate']))) : 0;
		$user->address = $db->prepare($_POST['address']);
		$user->zipcode = $db->prepare($_POST['zipcode']);
		$user->city = $db->prepare($_POST['city']);
		$user->salary = str_replace(",", ".", $db->prepare($_POST['salary']));
		$user->static_salary = str_replace(",", ".", $db->prepare($_POST['static_salary']));
		$user->static_salary = str_replace(" ", "", $user->static_salary);
		$user->percentage = $_POST['percentage'] ? $db->prepare($_POST['percentage']) : 100;
		$user->no_extra = $_POST['no_extra'] ? 1 : 0;
		$user->department_id = $db->prepare($_POST['department_id']);
		$user->access_level = (int)$_POST['access_level'];
		$user->locked = (int)$_POST['locked'];
		
		$user->set_validates("name", "required", "Navn");
		$user->set_validates("email", "required", "E-post");
	
		$user->validate();
		
		if ( ! $user->errors)
		{
			if ( ! $user->exists())
			{
				$password = generate_password();
				$user->password = sha1($user->email . $settings->sha1_seed . $password);
				
				// Create email
				$subject = 'Brukernavn og passord for ansattesider hos Kjøkkenskapet';
				$html = "<h2>$subject</h2>
						<p>Her f&oslash;lger ditt brukernavn og passord til ansattesider hos Kj&oslash;kkenskapet.</p>
						<p>
							<strong>Brukernavn:</strong> {$user->email}<br />
							<strong>Passord:</strong> {$password}
						</p>
						<p>Bruk f&oslash;lgende adresse for &aring; logge deg inn og se vaktlister, registrere timer m.m.:<br />
							<a href=\"http://kjokkenskapet.no{$settings->base_url}/innlogging\">http://kjokkenskapet.no{$settings->base_url}/innlogging</a>
						</p>
						<p>
							Etter at du har logget deg inn kan du endre passordet ditt (anbefales).
						</p>
						<p>Med vennlig hilsen<br />
						Kj&oslash;kkenskapet</p>";
				
				$mail = $settings->local == true ? new PHPMailerLocal(true) : new PHPMailerKjokkenskapet(true); // the true param means it will throw exceptions on errors, which we need to catch
			
				try {
					$mail->Subject = $subject;
					$mail->AddAddress($user->email, $user->name);
					$mail->SetFrom('post@kjokkenskapet.no', 'Kjøkkenskapet');
					$mail->MsgHTML($html);
					$mail->Send();
				
				} catch (phpmailerException $e) {
					$user->errors = array(array($e->errorMessage())); //Pretty error messages from PHPMailer
				} catch (Exception $e) {
					$user->errors = array(array($e->getMessage())); //Boring error messages from anything else!
				}
			}
			

			if ( ! $user->errors)
			{
				$user->save();
				$notice = "<p>Brukeren ble lagret.<p>";
			}
		}
	}
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/users/edit.php");	// View
	require_once("../views/default/footer.php");		// Footer

}

// PATH: /users/1/
if ($action == "show") {
	
	// GET PERSON AND CORRESPONDING COMPANY
	$user = new User($db, array("id" => $id));
		
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/users/show.php");		// View
	require_once("../views/default/footer.php");		// Footer

}

// PATH: /users
if ($action == "index") {
	
	// GET PERSON AND CORRESPONDING COMPANY
	$user = new User($db);
	
	$users = $user->get_all(array('order_by' => 'name'));
	
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/users/index.php");		// View
	require_once("../views/default/footer.php");		// Footer

}

// DELETE
if ($action == "delete")
{	
	if ($current_user->access_level < 1)
		$security->no_access();
	
	// Delete user
	$user = new User($db, array("id" => $id));
	$user->delete();
	
	// Redirect back
	if (isset($_GET['return_url']))
	{
		$url = urldecode($_GET['return_url']);
		header('Location: ' . $url);
		exit;
	}
	else
	{
		echo "Bruker slettet.";
	}
}


function generate_password()
{
	$length = 10;
	$str = "";
	
	for ($i = 0; $i < 10; $i++)
	{
		$rand = rand(0, 61);
		
		if ($rand <= 9)
			$rand += 48;
		else if ($rand > 9 && $rand <= 35)
			$rand += 55;
		else
			$rand += 61;
	
		$str .= chr($rand);
	}
	
	return $str;
}

?>