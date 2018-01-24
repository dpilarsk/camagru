<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
	Autoloader::register();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$user = new User($db);

	if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		echo "Email invalide !";
		die();
	}
	$user->checkMail($_POST['email']);
?>
