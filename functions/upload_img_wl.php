<?php

require_once '../Autoloader.php';
Autoloader::register();
require_once '../config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$user = new User($db);
$picture = new Picture($db);
if (!isset($_FILES['uploadPic']) || !isset($_POST['token']) || !isset($_POST['layer_id']))
{
    echo 'Veuillez choisir un filtre et uploader votre image !';
    die();
}
if (!isset($_POST['webcam']))
{
	$picture->upload_img_wl($_FILES['uploadPic'], $_POST['token'], $_POST['layer_id']);
}
else
{
	$picture->upload_img_wc($_POST['webcam'], $_POST['token'], $_POST['layer_id']);
}

?>