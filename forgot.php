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
	<form action="/register.php" method="POST">
		<input type="text" placeholder="Email" name="email" id="email">
		<input type="submit" id="submit" value="Demande de mot de passe" disabled>
	</form>
</div>
<?php include_once 'resources/partials/footer.php'?>
</body>
</html>