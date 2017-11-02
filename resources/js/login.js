var form = document.getElementById("login")
var xhr = getHttpRequest()
form.addEventListener("submit", function (e) {
	e.preventDefault()
	var data = new FormData(form)
	xhr.open('POST', '/functions/login.php', true)
	xhr.send(data)
})
xhr.onreadystatechange = function () {
	var res = document.getElementById("res")
	if (xhr.readyState === 4 && xhr.status === 200) {
		res.innerHTML = xhr.responseText
		document.getElementById('submit').disabled = true
		setTimeout(function () {
			window.location.replace('/');
		}, 3000)
	}
	else if (xhr.status >= 400)
		res.innerHTML = "Impossible de joindre le serveur !"
}