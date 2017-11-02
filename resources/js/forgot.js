document.getElementById("email").addEventListener("input", function (e) {
	if (this.value.length <= 0)
	{
		this.style.borderBottomColor = "red"
		document.getElementById("submit").disabled = true
	}
	else
	{
		this.style.borderBottomColor = ""
		document.getElementById("submit").disabled = false
	}
});
var form = document.getElementById("forgot")
var xhr = getHttpRequest()
form.addEventListener("submit", function (e) {
	e.preventDefault()
	var data = new FormData(form)
	xhr.open('POST', '/functions/forgot.php', true)
	xhr.send(data)
})
xhr.onreadystatechange = function () {
	var res = document.getElementById("res")
	if (xhr.readyState === 4 && xhr.status === 200) {
		console.log(xhr.responseText)
		res.innerHTML = xhr.responseText
	}
	else if (xhr.status >= 400)
		res.innerHTML = "Impossible de joindre le serveur !"
}