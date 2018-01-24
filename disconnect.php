<?php
if (!isset($_SESSION['id']))
{
	header('Location: /');
	$_SESSION['flash'] = "Veuillez vous connecter afin d'acceder a cette page";
}
session_start();
require_once 'Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$_SESSION = array();
session_destroy();
$_SESSION['flash'] = 'Vous etes bien deconnecte !';
header('Location: /');
?>
