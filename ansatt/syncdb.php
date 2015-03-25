<?php
require_once("models/Settings.php");
require_once("helpers/Security.php");
require_once("helpers/DbConnect.php");

$db = new DbConnect();
$db->show_errors();

// Create model array, get file list of folder "models"
$models = array();
$files = scandir('models');

// Put valid file names in model array
foreach ($files as $file)
	if (strlen($file) > 2)
		$models[] = substr($file, 0, -4);

// First create settings table (all the other models rely on this to exist)
require_once("models/Settings.php");
$settings = new Settings($db, array('install' => 1));
$settings->db_up();

// Create tables for all valid models
foreach ($models as $model)
{
	// Create model from class
	require_once("models/" . $model . ".php");
	$current_model = new $model($db);
	
	// Run model's db setup method
	if (get_class($current_model) != "Model")
		$current_model->db_up();	
}

echo "Installasjon fullf&oslash;rt.";

?>