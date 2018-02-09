<?php
	session_start();
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
	<title>Camagru - Accueil</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<div class="container">
		<?php if (isset($_SESSION['flash'])) { ?>
			<p class="error"><?= $_SESSION['flash'] ?></p>
			<?php unset($_SESSION['flash']);
		} ?>
		<h1 style="text-align: center;font-size: 5em;">Bienvenue sur Camagru.</h1>
		<center>
			<img src="https://images.unsplash.com/photo-1493160221091-3926257964be?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=601277bb9b64b28f5122a43e7fae386d&auto=format&fit=crop&w=2700&q=80" alt="photo" width="60%">
		</center>
	</div>
	<?php include_once 'resources/partials/footer.php'; ?>
</body>
</html>
