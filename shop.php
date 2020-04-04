<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    session_start();
    if(isset($_SESSION['drasin']))
    {
        if($_SESSION['drasin'] != 667)
        {
            header("Location: shopLogin.php");
        }
    }
    else
    {
        header("Location: shopLogin.php");

    }
?>

<!DOCTYPE HTML>
<HEAD>
	<TITLE>Drasin</TITLE>
	
	<link href="img/logos/favicon.png" rel="icon">
	<link href="css/admin.css" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700&display=swap" rel="stylesheet">
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	
</HEAD>
<BODY id="main" style="border-radius: 10px;" class="no-scroll">
	<div id="oldbrowser" style="display: none;"></div>
	
	<script>
		//GimmeNiceIntro
		body = document.getElementById("main");
		body.style.opacity = 0;
		
		setTimeout(function() {
			body.style.opacity = 1;
		}, 0);
	</script>
	
	<div class="weatherinfo" id="sli">
		<div class="appinfo">
			<img src="img/logos/Drasin-Logo.png" draggable="false" class="appico">
		</div>
		
		<p class="info">Czynności</p>
		
		<a href="shopAssortment.php" class="nostyle">
			<div class="param">
				<img src="img/icons/form.png" class="wicon">
				<div class="sdat">
					<p class="stitle" id="1m">Asortyment</p>
					<p class="sdetail">Dostosuj dostępność i cenę produktów w Twoim sklepie</p>
				</div>
			</div>
		</a>
        <a href="activeOrders.php" class="nostyle">
			<div class="param">
				<img src="img/icons/form.png" class="wicon">
				<div class="sdat">
					<p class="stitle" id="1m">Zamówienia - Wystawienie oferty</p>
					<p class="sdetail">Tutaj znajdują się zamówienia, którym możesz wystawić ofertę</p>
				</div>
			</div>
		</a>
		<a href="shopOrders.php" class="nostyle">
			<div class="param">
				<img src="img/icons/form.png" class="wicon">
				<div class="sdat">
					<p class="stitle" id="1m">Zamówienia - Przyjęte oferty</p>
					<p class="sdetail">Tutaj znajdują się wszystkie zamówienia powiązane z twoim sklepem</p>
				</div>
			</div>
		</a>
		<a href="shopEditAccount.php" class="nostyle">
			<div class="param">
				<img src="img/icons/student.png" class="wicon">
				<div class="sdat">
					<p class="stitle" id="1m">Ustawienia</p>
					<p class="sdetail">Przeglądaj i konfiguruj opcje konta</p>
				</div>
			</div>
		</a>
		<a href="logout.php" class="nostyle">
			<div class="param">
				<img src="img/icons/house.png" class="wicon">
				<div class="sdat">
					<p class="stitle" id="1m">Wyloguj się</p>
					<p class="sdetail">Bezpiecznie zamyka sesje</p>
				</div>
			</div>
		</a>

		<div class="spacer"></div>
	</div>
	
	<script src="js/general.js"></script>
</BODY>
