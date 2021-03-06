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
	<title>Camagru - Inscription</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<form action="#" method="POST" id="register">
			<input type="text" placeholder="Nom d'utilisateur (3 caract&edot;res ou plus)" name="username" id="username">
			<input type="password" placeholder="Mot de passe (8 caract&edot;res | + (Majuscule & Chiffre & Caract&edot;re sp&eacute;cial))" name="password" id="password">
			<input type="email" placeholder="Email" name="email" id="email">
			<input type="submit" id="submit" value="Inscription" disabled>
		</form>
		<div class="res" id="res"></div>
	</div>
	<?php include_once 'resources/partials/footer.php'?>
	<script src="resources/js/ajax.js"></script>
	<script src="resources/js/register.js"></script>
</body>
</html>
