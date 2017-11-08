<?php

require_once '../Autoloader.php';
Autoloader::register();
require_once '../config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$picture = new Picture($db);

if (!isset($_POST['token']) || !isset($_POST['picture_id']))
{
	die();
}
$picture->like($_POST['token'], $_POST['picture_id']);
?>