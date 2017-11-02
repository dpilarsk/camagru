<?php
	session_start();
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
	<title>Camagru - Accueil</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<div class="container">
		<?php if (isset($_SESSION['flash'])){ ?>
			<p class="error"><?= $_SESSION['flash'] ?></p>
		<?php }
		$_SESSION = array(); ?>
	</div>
	<?php include_once 'resources/partials/footer.php'; ?>
</body>
</html>