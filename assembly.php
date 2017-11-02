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
	<title>Camagru - Montage photo</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
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
			</div>
			<br><br>
			<div class="side"><h3>Previous pics</h3></div>
		</div>
	</div>
	<script>
		var video = document.getElementById("video"),
			canvas = document.getElementById('canvas'),
			button = document.getElementById('pic'),
			streaming = false,
			width = 320,
			height = 0

		navigator.getMedia = ( navigator.getUserMedia ||
			navigator.webkitGetUserMedia ||
			navigator.mozGetUserMedia ||
			navigator.msGetUserMedia);

		navigator.getMedia({
			video: true,
			audio: false
		},
		function (stream) {
			if (navigator.mozGetUserMedia)
			{
				video.mozSrcObject = stream;
			}
			else
			{
				var vendorURL = window.URL || window.webkitURL
				video.src = vendorURL.createObjectURL(stream)
			}
			video.play()
		},
		function (err) {
			console.log("Something happened: " + err)
		})
		video.addEventListener('canplay', function (e) {
			if (!streaming)
			{
				height = video.videoHeight / (video.videoWidth / width)
				video.setAttribute('width', width)
				video.setAttribute('height', height)
				canvas.setAttribute('width', width)
				canvas.setAttribute('height', height)
				streaming = true
			}
		}, false)
	</script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>