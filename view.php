<?php
session_start();
if (!isset($_GET['id']))
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
$comments = new Comment($db);
$user = new User($db);
if ($picture === 0)
{
	header('Location: /');
	$_SESSION['flash'] = "Image non valide";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Camagru - Montage photo</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
<?php include_once 'resources/partials/header.php'; ?>
<br>
<div class="container">
	<div class="preview">
		<div class="main">
			<img src="<?= $picture['path']; ?>" alt="<?= $picture['id']; ?>" width="90%">
			<div class="res" id="res"></div>
		</div>
		<br><br>
		<div class="side">
			<h3>Comments</h3>
			<hr>
			<form action="#" id="comment">
				<input type="text" placeholder="Commentaire" name="commentContent" id="commentContent">
				<input type="text" value="<?= $_SESSION['token'] ?>" name="token" style="display: none;">
				<input type="submit" name="submit" id="submit" disabled>
			</form>
			<hr>
			<div id="comments">
				<?php $comments = $comments->getLastComments($picture['id']);
				for ($i = 0; $i < count($comments); $i++)
				{ ?>
					<div class="comment">
						<h3><?= $user->getUser($comments[$i]['user_id'])['login']; ?></h3>
						<hr>
						<p><?= $comments[$i]['comment']; ?></p>
					</div>
					<br>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script src="resources/js/ajax.js"></script>
<?php include_once 'resources/partials/footer.php'?>
</body>
</html>