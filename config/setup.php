<?php
require_once '../Autoloader.php';
Autoloader::register();
require_once './database.php';

$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->init_database("camagru");
?>