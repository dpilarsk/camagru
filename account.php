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
	<title>Camagru - compte</title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<meta name=""viewport content="width=device-width"/>
</head>
<body>
	<?php include_once 'resources/partials/header.php'; ?>
	<br>
	<div class="container">
		<div class="assembly">
			<div class="main">
				<br>
				<form action="#" method="POST" id="changePass">
					<input type="password" name="newPass" id="newPass" placeholder="Nouveau mot de passe">
					<input type="submit" id="submit1" value="Changer le mot de passe" disabled>
				</form>
				<dv class="res1" id="res1"></dv>
				<hr>
				<?php if ($_SESSION['role'] == 'admin'){ ?>
					<form action="#" method="POST" enctype="multipart/form-data" id="layout">
						<input type="text" placeholder="Nom" name="name">
						Choisir un filtre:
						<input type="file" name="uploadPic" id="uploadPic">
						<input type="submit" value="Upload" name="submit">
					</form>
					<div class="res" id="res"></div>
				<?php } ?>
			</div>
		</div>
	</div>
	<script src="resources/js/ajax.js"></script>
	<script>
		<?php if ($_SESSION['role'] == 'admin'){ ?>
			var form = document.getElementById("layout")
			var xhr = getHttpRequest()
			form.addEventListener("submit", function (e) {
				e.preventDefault()
				var data = new FormData(form)
				xhr.open('POST', '/functions/upload_layout.php', true)
				xhr.send(data)
			})
			xhr.onreadystatechange = function () {
				var res = document.getElementById("res")
				if (xhr.readyState === 4 && xhr.status === 200) {
					res.innerHTML = xhr.responseText
				}
				else if (xhr.status >= 400)
					res.innerHTML = "Impossible de joindre le serveur !"
			}
		<?php } ?>
		document.getElementById("newPass").addEventListener("input", function (e) {
			var regex = new RegExp("^(?=.*[A-Z])(?=.*\\d)(?=.*[$@$!%*?&])[A-Za-z\\d$@$!%*?&]{8,}")
			if (!regex.test(this.value) || this.value.length > 254)
			{
				this.style.borderBottomColor = "red"
				document.getElementById("submit1").disabled = true
			}
			else
			{
				this.style.borderBottomColor = ""
				document.getElementById("submit1").disabled = false
			}
		});
		var form2 = document.getElementById("changePass")
		var xhr1 = getHttpRequest()
		form2.addEventListener("submit", function (e) {
			e.preventDefault()
			var data = new FormData(form2)
			data.append('token', "<?= $_SESSION['token']; ?>")
			xhr1.open('POST', '/functions/change_pass.php', true)
			xhr1.send(data)
		})
		xhr1.onreadystatechange = function () {
			var res = document.getElementById("res1")
			if (xhr1.readyState === 4 && xhr1.status === 200) {
				res.innerHTML = xhr1.responseText
			}
			else if (xhr1.status >= 400)
				res.innerHTML = "Impossible de joindre le serveur !"
		}
	</script>
	<?php include_once 'resources/partials/footer.php'?>
</body>
</html>