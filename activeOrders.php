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

    function MakeOrders()
    {
        require_once("db.php");
        $shopId = $_SESSION['draSId'];

        $sql = sprintf("SELECT * FROM READYTOGO WHERE _id_shop = %d AND _status=2",
            intval($shopId)
        );
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {
                echo '
                <a href="shopShowOrder.php?id='.$row['_id_order'].'" class="nostyle">
                    <div class="param">
                        <img src="img/icons/form.png" class="wicon">
                        <div class="sdat">
                            <p class="stitle" id="1m">Zamówienie nr. '.$row['_id_order'].' - '.$row['_price'].' zł</p>
                            <p class="sdetail">Zobacz szczegóły zamówienia</p>
                        </div>
                    </div>
                </a>
                ';
            }
        }
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
		
		<p class="info">Twoje zamówienia</p>
        
        <?php
            MakeOrders();
        ?>
		
		<div class="spacer"></div>
	</div>
	
	<script src="js/general.js"></script>
</BODY>
