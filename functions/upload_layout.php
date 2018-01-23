<?php
session_start();
require_once '../Autoloader.php';
Autoloader::register();
require_once '../config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$user = new User($db);
$picture = new Picture($db);
$picture->upload_layout($_POST['name'], $_FILES['uploadPic'], $_SESSION['id']);

?>
