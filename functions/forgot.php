<?php

	require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
	Autoloader::register();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$user = new User($db);

	if (strlen($_POST['email']) <= 0 || filter_var($POST['email'], FILTER_VALIDATE_EMAIL))
	{
		echo "Email invalide !";
		die();
	}
	$user->checkMail($_POST['email']);
?>
