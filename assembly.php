<?php
	session_start();
	if (!isset($_SESSION['id']))
	{
		header('Location: /');
		$_SESSION['flash'] = "Veuillez vous connecter afin d'acceder a cette page";
	}
	require_once 'Autoloader.php';
	Autoloader::register();
	require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
	$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
	$db = $db->connect("camagru");
	$layers = new Picture($db);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Camagru - Montage photo</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<div class="assembly">
			<div class="main">
				<video id="video"></video>
				<button id="pic">Take a pic</button>
				<canvas id="canvas"></canvas>
				<canvas id="apercu" style="display: none"></canvas>
				<form action="#" method="POST" enctype="multipart/form-data" id="picture">
					Choisir une image:
					<input type="file" name="uploadPic" id="uploadPic">
					<input type="text" name="webcam" value="" id="webcam" style="display: none;">
					<input type="text" name="token" value="<?= $_SESSION['token'] ?>" style="display: none">
					<input type="text" name="layer_id" value="" id="layer_id" style="display: none">
					<input type="submit" value="Upload" id="uploadButton" name="submit" disabled>
				</form>
				<div class="res" id="res"></div>
				<div class="layouts" id="layouts">
					<?php $layers = $layers->getLayers();
					for ($i = 0; $i < count($layers); $i++)
					{
						echo "<img src=\"" . $layers[$i]['path'] . "\" alt=\"" . $layers[$i]['id'] . "\" width=\"255px\" height=\"200px\">";
					}
					?>
				</div>
			</div>
			<br><br>
			<div class="side">
				<h3>Previous pics</h3>
				<div id="previousPic"></div>
			</div>
		</div>
	</div>
	<script src="resources/js/ajax.js"></script>
	<script>
		function getLastPics()
		{
			var xhr2 = getHttpRequest()
			xhr2.open('POST', 'resources/partials/previous_pic.php', true)
			var id = new FormData()
			id.append('token', "<?= $_SESSION['token'] ?>")
			xhr2.send(id)
			xhr2.onreadystatechange = function () {
				if (xhr2.readyState == 4 && xhr2.status === 200)
				{
					previous.innerHTML = xhr2.responseText
				}
			}
		}
	</script>
	<script src="resources/js/assembly.js"></script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>
