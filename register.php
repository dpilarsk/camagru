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
			<input type="text" placeholder="Nom d'utilisateur (3 caract&edot;res ou plus)" name="username" id="username">
			<input type="password" placeholder="Mot de passe (8 caract&edot;res ou plus (Une majuscule, un chiffre et un caract&edot;re sp&eacute;cial))" name="password" id="password">
			<input type="email" placeholder="Email" name="email" id="email">
			<input type="submit" id="submit" disabled>
		</form>
	</div>
	<script>
		 document.getElementById("username").addEventListener("input", function (e) {
			if ((this.value.length < 3 || this.value.length > 254 || this.value.indexOf(' ') > -1) && this.value.length > 0)
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
		 document.getElementById("password").addEventListener("input", function (e) {
			 if ((this.value.length < 8 || this.value.length > 254) && this.value.length > 0)
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
	</script>
</body>
</html>