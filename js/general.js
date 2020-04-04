/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
document.getElementById('log').onclick = function() {
	document.getElementById('1').classList.add("noview");
	document.getElementById('2').classList.remove("noview");
}

document.getElementById('rej').onclick = function() {
	document.getElementById('1').classList.add("noview");
	document.getElementById('3').classList.remove("noview");
}
