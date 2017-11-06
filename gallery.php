<?php
session_start();
require_once 'Autoloader.php';
Autoloader::register();
require_once 'config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$pictures = new Picture($db);
$user = new Picture($db);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Camagru - Galerie</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<div class="gallery">
			<?php
			$pictures = $pictures->getAllPics();
			for ($i = 0; $i < count($pictures); $i++)
			{ ?>
				<div class="card">
					<a href="view.php?id=<?= $pictures[$i]['id']; ?>"><img src="<?= $pictures[$i]['path']; ?>" alt="<?= $i; ?>"></a>
					<title class="user"><?= $user->getUser($pictures[$i]['user_id']); ?></title>
				</div>
			<?php } ?>
		</div>
	</div>
	<script src="resources/js/gallery.js"></script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>