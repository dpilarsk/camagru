<?php

	require_once '../Autoloader.php';
	Autoloader::register();
	require_once '../config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$user = new User($db);

	if (strlen($_POST['username']) < 3 || preg_match('/[^a-zA-Z\d]/', $_POST['username']))
	{
		http_response_code(412);
		echo "Nom d'utilisateur invalide !";
	}
	else if (!preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/", $_POST['password']))
	{
		http_response_code(412);
		echo "Votre mot de passe ne contient pas au moins une majuscule, un chiffre, un caract&edot;re sp&eacute;cial ou bien n'a pas une longueur d'au moins 8 !";
	}
	else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		http_response_code(412);
		echo "Votre adresse email est invalide !";
	}
	else
		$user->register($_POST);
?>
