<?php
session_start();
if (!isset($_GET['id']))
{
	header('Location: /');
	$_SESSION['flash'] = "Image non valide";
}
require_once 'Autoloader.php';
Autoloader::register();
require_once 'config/database.php';
$db = new Database($DB_DSN, $DB_USER, $DB_PASSWORD);
$db = $db->connect("camagru");
$picture = new Picture($db);
$picture = $picture->getPic($_GET['id']);
$likes = new Picture($db);
$comments = new Comment($db);
$user = new User($db);
if ($picture === 0)
{
	header('Location: /');
	$_SESSION['flash'] = "Image non valide";
}
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
	<div class="preview">
		<div class="main">
			<img src="<?= $picture['path']; ?>" alt="<?= $picture['id']; ?>" width="90%">
			<div class="opinion">
				<div class="like" id="like" style="display: inline;<?php if ($likes->getLikeUser($_SESSION['token'], $_GET['id'])){ ?> border: solid 1px blue;<?php } ?>">
					<svg enable-background="new 0 0 32 32" height="32px" id="Layer_1" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="like"><path id="likeSVG" clip-rule="evenodd" d="M29.164,10.472c-1.25-0.328-4.189-0.324-8.488-0.438   c0.203-0.938,0.25-1.784,0.25-3.286C20.926,3.16,18.312,0,16,0c-1.633,0-2.979,1.335-3,2.977c-0.022,2.014-0.645,5.492-4,7.256   c-0.246,0.13-0.95,0.477-1.053,0.522L8,10.8C7.475,10.347,6.747,10,6,10H3c-1.654,0-3,1.346-3,3v16c0,1.654,1.346,3,3,3h3   c1.19,0,2.186-0.719,2.668-1.727c0.012,0.004,0.033,0.01,0.047,0.012c0.066,0.018,0.144,0.037,0.239,0.062   C8.972,30.352,8.981,30.354,9,30.359c0.576,0.143,1.685,0.408,4.055,0.953C13.563,31.428,16.247,32,19.027,32h5.467   c1.666,0,2.867-0.641,3.582-1.928c0.01-0.02,0.24-0.469,0.428-1.076c0.141-0.457,0.193-1.104,0.023-1.76   c1.074-0.738,1.42-1.854,1.645-2.58c0.377-1.191,0.264-2.086,0.002-2.727c0.604-0.57,1.119-1.439,1.336-2.766   c0.135-0.822-0.01-1.668-0.389-2.372c0.566-0.636,0.824-1.436,0.854-2.176l0.012-0.209C31.994,14.275,32,14.194,32,13.906   C32,12.643,31.125,11.032,29.164,10.472z M7,29c0,0.553-0.447,1-1,1H3c-0.553,0-1-0.447-1-1V13c0-0.553,0.447-1,1-1h3   c0.553,0,1,0.447,1,1V29z M29.977,14.535C29.957,15.029,29.75,16,28,16c-1.5,0-2,0-2,0c-0.277,0-0.5,0.224-0.5,0.5S25.723,17,26,17   c0,0,0.438,0,1.938,0s1.697,1.244,1.6,1.844C29.414,19.59,29.064,21,27.375,21C25.688,21,25,21,25,21c-0.277,0-0.5,0.223-0.5,0.5   c0,0.275,0.223,0.5,0.5,0.5c0,0,1.188,0,1.969,0c1.688,0,1.539,1.287,1.297,2.055C27.947,25.064,27.752,26,25.625,26   c-0.719,0-1.631,0-1.631,0c-0.277,0-0.5,0.223-0.5,0.5c0,0.275,0.223,0.5,0.5,0.5c0,0,0.693,0,1.568,0   c1.094,0,1.145,1.035,1.031,1.406c-0.125,0.406-0.273,0.707-0.279,0.721C26.012,29.672,25.525,30,24.494,30h-5.467   c-2.746,0-5.47-0.623-5.54-0.639c-4.154-0.957-4.373-1.031-4.634-1.105c0,0-0.846-0.143-0.846-0.881L8,13.563   c0-0.469,0.299-0.893,0.794-1.042c0.062-0.024,0.146-0.05,0.206-0.075c4.568-1.892,5.959-6.04,6-9.446c0.006-0.479,0.375-1,1-1   c1.057,0,2.926,2.122,2.926,4.748C18.926,9.119,18.83,9.529,18,12c10,0,9.93,0.144,10.812,0.375C29.906,12.688,30,13.594,30,13.906   C30,14.249,29.99,14.199,29.977,14.535z" fill="#000000" fill-rule="evenodd"/></g></svg>
					<p class="number" id="likeNum" style="display: inline;"><?= $likes->getLikes($picture['id']); ?></p>
				</div>
			</div>
		</div>
		<br><br>
		<div class="side">
			<h3>Comments</h3>
			<hr>
			<div id="res"></div>
			<form action="#" id="comment">
				<input type="text" placeholder="Commentaire" name="commentContent" id="commentContent">
				<input type="text" value="<?= $_SESSION['token'] ?>" name="token" style="display: none;">
				<input type="submit" name="submit" id="submit" disabled>
			</form>
			<hr>
			<div id="comments">
			</div>
		</div>
	</div>
</div>
<script src="resources/js/ajax.js"></script>
<script>
	function getLastComments()
	{
		var xhr2 = getHttpRequest()
		xhr2.open('POST', 'resources/partials/last_comments.php', true)
		var id = new FormData()
		id.append('id', "<?= $_GET['id'] ?>")
		xhr2.send(id)
		xhr2.onreadystatechange = function () {
			if (xhr2.readyState == 4 && xhr2.status === 200)
			{
				document.getElementById('comments').innerHTML = xhr2.responseText
			}
		}
	}
</script>
<script>
	getLastComments()
	var form = document.getElementById('comment')
	document.getElementById("commentContent").addEventListener("input", function (e) {
		if (this.value.length <= 0 || !this.value.trim().length)
		{
			document.getElementById("submit").disabled = true
		}
		else
		{
			document.getElementById("submit").disabled = false
		}
	});
	var xhr = getHttpRequest()
	form.addEventListener("submit", function (e) {
		e.preventDefault()
		var data = new FormData(form)
		data.append('token', "<?= $_SESSION['token'] ?>")
		data.append('picture_id', "<?= $_GET['id'] ?>")
		document.getElementById('submit').disabled = true
		xhr.open('POST', '/functions/comment.php', true)
		xhr.send(data)
	})
	xhr.onreadystatechange = function () {
		var res = document.getElementById("res")
		if (xhr.readyState === 4 && xhr.status === 200) {
			getLastComments()
			document.getElementById('res').innerHTML = xhr.responseText
			document.getElementById('commentContent').value = ""
		}
	}
	var xhr1 = getHttpRequest()
	document.getElementById('like').addEventListener('click', function (e) {
		var data1 = new FormData()
		data1.append('token', "<?= $_SESSION['token']; ?>")
		data1.append('picture_id', "<?= $_GET['id']; ?>")
		xhr1.open('POST', '/functions/like.php')
		xhr1.send(data1)
	})
	xhr1.onreadystatechange = function () {
		if (xhr1.readyState === 4 && xhr1.status === 200) {
			var num = <?= $likes->getLikes($picture['id']); ?>;
			if (xhr1.responseText == "like")
			{
				document.getElementById('like').style.border = 'solid 1px blue'
				document.getElementById('likeNum').innerHTML = num > 0 ? num : num + 1
			}
			else
			{
				document.getElementById('like').style.border = ''
				document.getElementById('likeNum').innerHTML = num > 0 ? num - 1 : num
			}
		}
	}
</script>
<?php include_once 'resources/partials/footer.php'?>
</body>
</html>