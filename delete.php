<?php
session_start();
if (!isset($_GET['id']) || !isset($_SESSION['id']))
{
	header('Location: /');
	$_SESSION['flash'] = "Image non valide";
}
require_once 'Autoloader.php';
Autoloader::register();
require_once 'config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$picture = new Picture($db);
$picture = $picture->getPic($_GET['id']);
if ($picture === 0)
{
	header('Location: /');
	$_SESSION['flash'] = "Image non valide";
}
$picture = new Picture($db);
$picture->delete($_GET['id'], $_SESSION['token']);