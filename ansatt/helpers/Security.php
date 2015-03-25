<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/ansatt/models/Settings.php");

class Security {
	private $db;
	private $current_user;
	
	public function __construct($db)
	{
		$this->db = $db;
		
		$this->settings = new Settings($db);
		
		if (isset($_SESSION['user_hash']))
		{
			$this->current_user = new User($db, array("hash" => $this->db->prepare($_SESSION['user_hash'])));
		}
		else if (isset($_COOKIE['user_hash']) && $_COOKIE['user_hash'] != "")
		{
			$this->current_user = new User($db, array("hash" => $this->db->prepare($_COOKIE['user_hash'])));
		}
	}
	
	public function check()
	{
		if ( ! $this->current_user)
		{
			$url = $this->settings->base_url . "/innlogging?return_url=" . urlencode($_SERVER['REQUEST_URI']);
			header('Location: ' . $url);
			exit;
		}
	}
	
	public function no_access()
	{
		if ($this->current_user)
			$url = $this->settings->base_url;
		else
			$url = $this->settings->base_url . '/innlogging?return_url=' . urlencode($_SERVER['REQUEST_URI']);
		
		header('Location: ' . $url);
		exit;
	}
	
	public function __get($key)
	{
		return $this->$key;
	}
	
	public function __toString()
	{
		return "Class: " . get_class($this);
	}
}

?>