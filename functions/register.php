<?php

	require_once '../Autoloader.php';
	Autoloader::register();
	require_once '../config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$user = new User($db);

	if (strlen($_POST['username']) < 3)
	{
		echo "Nom d'utilisateur invalide !";
		die();
	}
	else if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/", $_POST['password']))
	{
		echo "Votre mot de passe ne contient pas au moins une majuscule, un chiffre, un caract&edot;re sp&eacute;cial ou bien n'a pas une longueur d'au moins 8 !";
		die();
	}
	else if (filter_var($POST['email'], FILTER_VALIDATE_EMAIL))
	{
		echo "Votre adresse email est invalide !";
		die();
	}

	$user->register($_POST);
?>