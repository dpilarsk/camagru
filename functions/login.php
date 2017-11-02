<?php

	require_once '../Autoloader.php';
	Autoloader::register();
	require_once '../config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$user = new User($db);
	$user->login($_POST['username'], $_POST['password']);
?>