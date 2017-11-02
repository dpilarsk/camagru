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
				<canvas id="apercu"></canvas>
				<form action="#" method="POST" enctype="multipart/form-data">
					Choisir une image:
					<input type="file" name="uploadPic" id="uploadPic">
					<input type="submit" value="Upload" name="submit">
				</form>
				<div class="layouts">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
					<img src="http://lorempicsum.com/rio/255/200/2" alt="">
				</div>
<!--				<img src="" alt="" id="photo">-->
			</div>
			<br><br>
			<div class="side">
				<h3>Previous pics</h3>
				<div class="card">
					<a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a>
				</div>
				<div class="card">
					<a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a>
				</div>
				<div class="card">
					<a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a>
				</div>
				<div class="card">
					<a href="#"><img src="http://lorempicsum.com/rio/255/200/2" alt=""></a>
				</div>
			</div>
		</div>
	</div>
	<script>
		var video = document.getElementById("video"),
			canvas = document.getElementById('canvas'),
			apercu = document.getElementById('apercu'),
			button = document.getElementById('pic'),
			photo = document.getElementById('photo'),
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
		button.addEventListener('click', function (e) {
			e.preventDefault()
			apercu.width = width
			apercu.height = height
			apercu.getContext('2d').drawImage(video, 0, 0, width, height)
//			var data = apercu.toDataURL('image/png')
//			photo.setAttribute('src', data)
		})
		button.disabled = true
	</script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>