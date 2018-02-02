<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/Autoloader.php';
Autoloader::register();
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
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
	<link rel="shortcut icon" href="/resources/images/favicon.ico">
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<div id="gallery">
		</div>
	</div>
	<script src="resources/js/gallery.js"></script>
	<script src="resources/js/ajax.js"></script>
	<script>
		function getPics(page)
		{
			var xhr2 = getHttpRequest()
			xhr2.open('POST', 'resources/partials/gallery_pic.php', true)
			var id = new FormData()
			id.append('page', page)
			xhr2.send(id)
			xhr2.onreadystatechange = function () {
				if (xhr2.readyState == 4 && xhr2.status === 200)
				{
					document.getElementById('gallery').innerHTML = document.getElementById('gallery').innerHTML + xhr2.responseText
				}
			}
		}
		getPics(1)
	</script>
	<script>
		var i = 1
		window.addEventListener('scroll', function () {
			if ((window.innerHeight + window.scrollY) >= (document.body.scrollHeight))
			{
				getPics(++i)
			}
		})
	</script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>
