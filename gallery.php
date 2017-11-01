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
	<title>Camagru - Gallerie</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
<?php include_once 'resources/partials/header.php'; ?>
<br>
<div class="container">
<!--	<a href="view.php?id=#"><span class="card"></span></a>-->
	<div class="gallery">
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
		<div class="card"><a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a></div>
	</div>
</div>
<?php include_once 'resources/partials/footer.php'?>
</body>
</html>