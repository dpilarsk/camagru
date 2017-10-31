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