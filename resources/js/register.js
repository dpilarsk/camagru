document.getElementById("username").addEventListener("input", function (e) {
	if ((this.value.length < 3 || this.value.length > 254 || this.value.indexOf(' ') > -1) && this.value.length > 0)
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

document.getElementById("password").addEventListener("input", function (e) {
	var regex = new RegExp("^(?=.*[A-Z])(?=.*\\d)(?=.*[$@$!%*?&])[A-Za-z\\d$@$!%*?&]{8,}")
	if (!regex.test(this.value) || this.value.length > 254)
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

var form = document.getElementById("register")
var xhr = getHttpRequest()
form.addEventListener("submit", function (e) {
	e.preventDefault()
	var data = new FormData(form)
	xhr.open('POST', '/functions/register.php', true)
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