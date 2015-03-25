<?php
require_once("PHPMailer.php");

Class PHPMailerKjokkenskapet extends PHPMailer {
	
	public function __construct($exceptions = false)
	{
		$this->CharSet = "UTF-8";
		$this->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
	}
}