<?php
require_once 'Autoloader.php';
Autoloader::register();
require_once 'config/database.php';
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
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<form action="/register.php" method="POST">
			<input type="text" placeholder="Nom d'utilisateur" name="username" id="username">
			<input type="password" placeholder="Mot de passe" name="password" id="password">
			<input type="submit" id="submit" value="Connexion" disabled>
			<a href="forgot.php">
				<button id="forgotPass">J'ai oubli&eacute;(e) mon mot de passe.</button>
			</a>
		</form>
	</div>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>