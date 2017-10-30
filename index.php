<?php
	require_once 'Autoloader.php';
	Autoloader::register();
	require_once 'config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db->connect("camagru");
//	$db->check_db_exist();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Camagru</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
<header>
	<nav>
		<ul>
			<li><a href="#">Accueil</a></li>
			<li><a href="#">Galerie</a></li>
			<li><a href="#">Connexion</a></li>
			<li><a href="#">Inscription</a></li>
			<li><a href="#">Se deconnecter</a></li>
			<li><a href="#">Mon compte</a></li>
		</ul>
	</nav>
</header>
</body>
</html>