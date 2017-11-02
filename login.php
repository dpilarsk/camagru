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
	<script>
		var form = document.getElementById("login")
		var xhr = getHttpRequest()
		form.addEventListener("submit", function (e) {
			e.preventDefault()
			var data = new FormData(form)
			xhr.open('POST', '/functions/login.php', true)
			xhr.send(data)
		})
		xhr.onreadystatechange = function () {
			var res = document.getElementById("res")
			if (xhr.readyState === 4 && xhr.status === 200) {
				res.innerHTML = xhr.responseText
			}
			else if (xhr.status >= 400)
				res.innerHTML = "Impossible de joindre le serveur !"
		}
	</script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>