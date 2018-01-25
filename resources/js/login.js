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
	if (xhr.readyState === 4) {
		res.innerHTML = xhr.responseText
		if (xhr.status === 204)
		{
			setTimeout(function () {
				window.location.replace('/');
			})
		}
	}
	else if (xhr.status >= 400)
		res.innerHTML = "Impossible de joindre le serveur !"
}
