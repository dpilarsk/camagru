<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$comment = new Comment($db);
if (!isset($_POST['commentContent']) || !isset($_POST['token']) || !isset($_POST['picture_id']) || strlen($_POST['commentContent']) <= 0)
{
	echo "<p>Commentaire trop court</p>";
	die();
}
http_response_code(204);
$comment->add($_POST['commentContent'], $_POST['token'], $_POST['picture_id']);
?>
