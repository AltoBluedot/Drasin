<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
	if(!isset($_GET['n']))
	{
		if((int)($_GET['n']) != 2)
		{
			header("Location: start/drasin-main.html");
		}
	}
?>

<!DOCTYPE HTML>
<HEAD>
	<TITLE>Drasin</TITLE>
	
	<link href="img/logos/favicon.png" rel="icon">
	<link href="css/login.css" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700&display=swap" rel="stylesheet">
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
</HEAD>
<BODY id="main" style="border-radius: 10px;">
	<div id="oldbrowser" style="display: none;"></div>
	
	<script>
		//GimmeNiceIntro
		body = document.getElementById("main");
		body.style.opacity = 0;
		
		setTimeout(function() {
			body.style.opacity = 1;
		}, 0);
	</script>
	
	<div class="midbox side" id="1">
		<img src="img/logos/Drasin-Logo.png" id="brand">
		
		<a href="customer.php"><input id="log" class="okform" style="background: black; color: white;" type="submit" value="Klient"></a>
		<a href="shop.php"><input id="rej" class="okform" type="submit" value="Sklep"></a>
	</div>
	
	<script src="js/general.js"></script>
</BODY>
