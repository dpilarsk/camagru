function getRandomColor() {
	var length = 6;
	var chars = '0123456789ABCDEF';
	var hex = '#';
	while(length--) hex += chars[(Math.random() * 16) | 0];
	return hex;
}
var titles = document.getElementsByClassName("user")
Array.prototype.forEach.call(titles, function (e) {
	e.style.background = getRandomColor()
})