<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$pictures = new Picture($db);
$user = new Picture($db);
$pictures = $pictures->getAllPics($_POST['page']);
for ($i = 0; $i < count($pictures); $i++)
{ ?>
	<div class="card">
		<a href="view.php?id=<?= $pictures[$i]['id']; ?>"><img src="<?= $pictures[$i]['path']; ?>" alt="<?= $i; ?>"></a>
		<title class="user"><?= $user->getUser($pictures[$i]['user_id']); ?></title>
	</div>
<?php } ?>
