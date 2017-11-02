<?php
session_start();
if (!isset($_SESSION['id']))
{
	header('Location: /');
	$_SESSION['flash'] = "Veuillez vous connecter afin d'acceder a cette page";
}
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
	<title>Camagru - Galerie</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
	<!--	<a href="view.php?id=#"><span class="card"></span></a>-->
		<div class="gallery">
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/futurama/255/200/5" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/futurama/255/200/2" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/nemo/255/200/2" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/nemo/255/200/5" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/simpsons/255/200/2" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/simpsons/255/200/5" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/up/255/200/2" alt=""></a>
				<title class="user">User</title>
			</div>
			<div class="card">
				<a href="#"><img src="http://lorempicsum.com/up/350/200/1" alt=""></a>
				<title class="user">User</title>
			</div>
		</div>
	</div>
	<script>
		function getRandomColor() {
			var length = 6;
			var chars = '0123456789ABCDEF';
			var hex = '#';
			while(length--) hex += chars[(Math.random() * 16) | 0];
			return hex;
		}
		var titles = document.getElementsByClassName("user")
		Array.prototype.forEach.call(titles, function (e) {
			e.style.background = getRandomColor()
		})
	</script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>