<?php
require_once("PHPMailerKjokkenskapet.php");

Class PHPMailerLocal extends PHPMailerKjokkenskapet {
	
	public function __construct($exceptions = false)
	{
		$this->IsSMTP(); // telling the class to use SMTP
		$mail->SMTPDebug = false;
		
		$this->Host       = "smtp.gmail.com"; // SMTP server
		$this->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
		$this->SMTPAuth   = true;                  // enable SMTP authentication
		$this->SMTPSecure = "ssl";                 // sets the prefix to the servier
		$this->Port       = 465;                    // set the SMTP port for the GMAIL server
		$this->Username   = "bjornar.tollaksen@gmail.com"; // SMTP account username
		$this->Password   = "frU1tc4k3*";        // SMTP account password
	}
}

?>