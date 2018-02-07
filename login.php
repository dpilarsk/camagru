<?php
session_start();
if (isset($_SESSION['id']))
{
	header('Location: /');
	$_SESSION['flash'] = "Veuillez vous deconnecter afin d'acceder a cette page";
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db->connect("camagru");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Camagru - Connexion</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<form action="#" method="POST" id="login">
			<input type="text" placeholder="Nom d'utilisateur" name="username" id="username">
			<input type="password" placeholder="Mot de passe" name="password" id="password">
			<input type="submit" id="submit" value="Connexion">
		</form>
		<div class="res" id="res"></div>
		<br>
		<a href="forgot.php">
			<button id="forgotPass">J'ai oubli&eacute;(e) mon mot de passe.</button>
		</a>
	</div>
	<script src="resources/js/ajax.js"></script>
	<script src="resources/js/login.js"></script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>
