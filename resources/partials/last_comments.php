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
$comments = new Comment($db);
$user = new User($db);
$comments = $comments->getLastComments($_POST['id']);
for ($i = 0; $i < count($comments); $i++)
{ ?>
	<div class="comment">
		<h3><?= $user->getUser($comments[$i]['user_id'])['login']; ?></h3>
		<hr>
		<p><?= htmlspecialchars($comments[$i]['comment']); ?></p>
	</div>
	<br>
<?php } ?>