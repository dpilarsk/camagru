<?php
	session_start();
	if (!isset($_SESSION['id']))
	{
		header('Location: /');
		$_SESSION['flash'] = "Veuillez vous connecter afin d'acceder a cette page";
	}
	require_once '../../Autoloader.php';
	Autoloader::register();
	require_once '../../config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$pictures = new Picture($db);
?>
<?php
$pictures = $pictures->getLastPics($_POST['token']);
for ($i = 0; $i < count($pictures); $i++)
{ ?>
	<div class="card">
		<a href="view.php?id=<?= $pictures[$i]['id']; ?>"><img src="<?= $pictures[$i]['path']; ?>" alt="<?= $i; ?>"></a>
	</div>
<?php } ?>