<?php

// HELPERS
require_once("../helpers/DbConnect.php");
require_once("../helpers/Security.php");
require_once("../models/Settings.php");
require_once("../helpers/PHPMailerKjokkenskapet.php");
require_once("../helpers/PHPMailerLocal.php");

// MODELS
require_once("../models/User.php");

// INSTANCES
$db = new DbConnect();
$db->show_errors();
$settings = new Settings($db);
$security = new Security($db);
$current_user = $security->current_user;

// Get methods
$action = $_GET['action'];

// LOGIN
if ($action == "login")
{
	function my_redirect($return_url = null)
	{	
		// Return user to the page they were looking for
		$url = $return_url ? $return_url : "/ansatt/";
					
		header('Location: ' . $url);
	}
	
	if ($current_user)
	{
		$current_user->last_login = date('Y-m-d H:i:s');
		$current_user->save();
		
		my_redirect($_GET['return_url']);
	}
	
	if (isset($_POST['submit']))
	{	
		$username = strtolower($db->prepare($_POST['username']));
		$password = $db->prepare($_POST['password']);
		$remember_me = $_POST['remember_me'] != "" ? true : false;
		
		$user = new User($db, array(	'username' => $username,
										'password' => $password,
										'sha1_seed' => $settings->sha1_seed));
										
		setcookie('username', $username, time()+3600*24*30, '/ansatt/', '.' . $settings->domain);
				
		if ( ! $user->exists())
		{
			$user->username = $username;
			$user->errors = array(array("error_description" => "Feil brukernavn eller passord."));
		}
		else if ($user->locked == 1)
		{
			$user->username = $username;
			$user->errors = array(array("error_description" => "Denne brukeren er sperret for innlogging. Vennligst kontakt din nærmeste leder for å gjenåpne kontoen."));
		}
		else
		{
			$_SESSION['user_hash'] = $user->password;
			
			if ($remember_me)
			{
				setcookie('user_hash', $user->password, time()+3600*24*14, '/ansatt/', '.' . $settings->domain);
			}
			
			$user->last_login = date('Y-m-d H:i:s');
			$user->save();
			
			my_redirect($_GET['return_url']);
		}
	}
	
	$username = $username ? $username : $_COOKIE['username'];
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/login/login.php");				// View
	require_once("../views/default/footer.php");		// Footer
	
}

// FORGOT PASSWORD
if ($action == "forgot")
{	
	if (isset($_POST['submit'])) {
		
		$user = new User($db, array("email" => $db->prepare($_POST['email'])));
		
		if ($user->exists() == false) {
			$user->email = $_POST['email'];
		} else {
			$user = new User($db, array("id" => $user->id));
			$subject = "Ditt passord hos Kjøkkenskapet";
			$hash = sha1($user->email . $settings->sha1_seed . date('Y-m-d H:i:s'));
			$user->password = $hash;
			$user->save();
					
			// Create email
			$html = $mail_header;
			$html .= "<h2>$subject</h2>
						<p>Du har bedt om &aring; gjenopprette passordet ditt hos Kj&oslash;kkenskapet.</p>
						<p>
							<strong>Benytt f&oslash;lgende lenke for &aring; gjenopprette passordet ditt:</strong><br />
							<a href=\"http://kjokkenskapet.no{$settings->base_url}/innlogging/reset?key=$hash\">http://kjokkenskapet.no{$settings->base_url}/innlogging/reset?key=$hash</a>
						</p>
						<p>
							Hvis du <em>ikke</em> har bedt &aring; om gjenopprette passordet ditt og likevel f&aring;r denne e-posten, vennligst meld fra til din n&aelig;rmeste overordnede om dette omg&aring;ende.
						</p>";
			$html .= $mail_footer;
			
			$mail = $settings->local == true ? new PHPMailerLocal(true) : new PHPMailerKjokkenskapet(true); // the true param means it will throw exceptions on errors, which we need to catch
					
			try {
				$mail->Subject = $subject;
				$mail->AddAddress($user->email, $user->name);
				$mail->SetFrom('post@kjokkenskapet.no', 'Kjøkkenskapet');
				$mail->MsgHTML($html);
				$mail->Send();
				
				$notice = "Lenke for &aring; gjenopprette passord er sendt til e-postadressen du oppga.";
			} catch (phpmailerException $e) {
				$user->errors = array(array('error_description' => $e->errorMessage())); //Pretty error messages from PHPMailer
			} catch (Exception $e) {
				$user->errors = array(array('error_description' => $e->getMessage())); //Boring error messages from anything else!
			}
		}
		
	}
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/login/forgot.php");				// View
	require_once("../views/default/footer.php");		// Footer
	
}

// RESET PASSWORD
if ($action == "reset")
{
	$hash = $db->prepare($_GET['key']);
	
	$user = new User($db, array("hash" => $hash));
	
	if ($user->exists() == false) {
		$user->errors = array(array("error_description" => "Ugyldig n&oslash;kkel for &aring; gjenopprette passord.", "error_code" => "non_existing"));
	} else {
		if (isset($_POST['submit'])) {
			$user->password = $db->prepare($_POST['password']);
			$user->set_validates("password", "required", "Passord");
			$user->validate();
			
			if ( ! $user->errors) {
				$user->password = sha1($user->email . $settings->sha1_seed . $user->password);
				$user->save();
				$_SESSION['user_hash'] = $user->password;
				
				$notice = "Nytt passord ble lagret.";
			}
		}
	}
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/login/reset.php");				// View
	require_once("../views/default/footer.php");		// Footer
	
}

// RESET PASSWORD
if ($action == "change")
{
	$user = $current_user;

	if (isset($_POST['submit']))
	{
		$old_password = $db->prepare($_POST['old_password']);
		$new_password = $db->prepare($_POST['new_password']);
		
		if ( ! $new_password)
			$user->errors = array(array('error_description' => "Nytt passord m&aring; fylles ut."));
		
		if ( ! $old_password)
			$user->errors = array(array('error_description' => "N&aring;v&aelig;rende passord m&aring; fylles ut."));
			
		
		if ( ! $user->errors)
		{
			if (sha1($user->email . $settings->sha1_seed . $old_password) == $user->password)
			{			
				$user->password = sha1($user->email . $settings->sha1_seed . $new_password);
				$user->save();
				$_SESSION['user_hash'] = $user->password;
				
				$notice = "Nytt passord ble lagret.";
			}
			else
			{
				$user->errors = array(array('error_description' => "Det n&aring;v&aelig;rende passordet du har skrevet inn er ikke riktig	."));
			}
		}
	}
	
	// VIEWS
	require_once("../views/default/header.php"); 		// Header
	require_once("../views/login/change_password.php");				// View
	require_once("../views/default/footer.php");		// Footer
	
}

if ($action == "sha1")
{
	$username = $_GET['username'];
	$password = $_GET['password'];
	
	echo sha1($username . $settings->sha1_seed . $password);
}

// LOG OUT
if ($action == "logout")
{
	session_destroy();
	setcookie('user_hash', '', time()-3600, '/ansatt/', '.' . $settings->domain);
	$current_user = null;
	
	$url = $settings->base_url;
	header('Location: ' . $url);
		
}

?>