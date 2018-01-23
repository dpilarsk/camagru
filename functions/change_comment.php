<?php
session_start();
require_once '../Autoloader.php';
Autoloader::register();
require_once '../config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$user = new User($db);

if (!isset($_POST['value']) || !isset($_POST['token']))
{
	echo "Veuillez vous connecter.";
	die();
}
else
{
	$user->changeCommentsEmail($_POST['value'], $_POST['token']);
	if ($_POST['value'] == 1)
		$_SESSION['get_email?'] = 1;
	else
		$_SESSION['get_email?'] = 0;
}
