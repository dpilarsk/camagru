<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$user = new User($db);

if (!isset($_POST['newPass']) || !preg_match("/^(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}/", $_POST['newPass']) || !isset($_POST['token']))
{
	echo "<p>Votre mot de passe ne contient pas au moins une majuscule, un chiffre, un caract&edot;re sp&eacute;cial ou bien n'a pas une longueur d'au moins 8 !</p>";
	die();
}

$user->changePass($_POST['newPass'], $_POST['token']);
