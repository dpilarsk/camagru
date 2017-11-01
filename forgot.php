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
	<title>Camagru - Oubli de mot de passe</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<form action="#" method="POST" id="forgot">
			<input type="text" placeholder="Email" name="email" id="email">
			<input type="submit" id="submit" value="Demande de mot de passe" disabled>
		</form>
		<div class="res" id="res"></div>
	</div>
	<?php include_once 'resources/partials/footer.php'?>
	<script src="resources/js/ajax.js"></script>
	<script>
		document.getElementById("email").addEventListener("input", function (e) {
			if (this.value.length <= 0)
			{
				this.style.borderBottomColor = "red"
				document.getElementById("submit").disabled = true
			}
			else
			{
				this.style.borderBottomColor = ""
				document.getElementById("submit").disabled = false
			}
		});
		var form = document.getElementById("forgot")
		var xhr = getHttpRequest()
		form.addEventListener("submit", function (e) {
			e.preventDefault()
			var data = new FormData(form)
			xhr.open('POST', '/functions/forgot.php', true)
			xhr.send(data)
		})
		xhr.onreadystatechange = function () {
			var res = document.getElementById("res")
			if (xhr.readyState === 4 && xhr.status === 200) {
				console.log(xhr.responseText)
				res.innerHTML = xhr.responseText
			}
			else if (xhr.status >= 400)
				res.innerHTML = "Impossible de joindre le serveur !"
		}
	</script>
</body>
</html>