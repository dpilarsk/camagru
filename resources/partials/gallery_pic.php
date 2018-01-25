<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$pictures = new Picture($db);
$user = new Picture($db);
if (!isset($_POST['page']))
{
	http_response_code(412);
	die('Erreur');
}
$pictures = $pictures->getAllPics($_POST['page']);
?>
<div class="gallery">
<?php
for ($i = 0; $i < count($pictures); $i++)
{ ?>
	<div class="card">
		<a href="view.php?id=<?= $pictures[$i]['id']; ?>"><img src="<?= $pictures[$i]['path']; ?>" alt="<?= $i; ?>"/></a>
		<title class="user"><?= $user->getUser($pictures[$i]['user_id']); ?></title>
	</div>
<?php } ?>
</div>
