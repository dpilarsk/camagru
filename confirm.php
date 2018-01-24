<?php
	require_once 'Autoloader.php';
	Autoloader::register();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$user = new User($db);

	if (!isset($_GET['login']) || !isset($_GET['token']))
	{
		echo 'Il manque des param&edot;tres !';
		die();
	}
	$user->checkToken($_GET['login'], $_GET['token']);
?>
