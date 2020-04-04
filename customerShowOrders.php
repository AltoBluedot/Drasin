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
        if($_SESSION['drasin'] != 776)
        {
            header("Location: customerLogin.php");
        }
    }
    else
    {
        header("Location: customerLogin.php");

    }

    function MakeOrders()
    {
        include "db.php";
        $customerId = $_SESSION['draCId'];

        $sql = sprintf("SELECT * FROM ORDERS WHERE _id_customer = %d AND _status != 9",
            intval($customerId)
        );
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {
                echo '
                <a href="customerShowOrder.php?id='.$row['_id'].'" class="nostyle">
                    <div class="param">
                        <img src="img/icons/form.png" class="wicon">
                        <div class="sdat">
                            <p class="stitle" id="1m">Zamówienie nr. '.$row['_id'].'</p>
                            <p class="sdetail">Zobacz szczegóły swojego zamówienia</p>
                        </div>
                    </div>
                </a>
                ';
            }
        }
    }

    function MakeEndOrders()
    {
        include "db.php";
        $customerId = $_SESSION['draCId'];

        $sql = sprintf("SELECT * FROM ORDERS WHERE _id_customer = %d AND _status = 9",
            intval($customerId)
        );
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {
                echo '
                <a href="customerShowOrder.php?id='.$row['_id'].'" class="nostyle">
                    <div class="param">
                        <img src="img/icons/form.png" class="wicon">
                        <div class="sdat">
                            <p class="stitle" id="1m">Zamówienie nr. '.$row['_id'].'</p>
                            <p class="sdetail">Zobacz szczegóły swojego zamówienia</p>
                        </div>
                    </div>
                </a>
                ';
            }
        }
    }

    function MakeAcceptedOrders()
    {
        include "db.php";
        $customerId = $_SESSION['draCId'];

        $sql = sprintf("SELECT * FROM ORDERS WHERE _id_customer = %d AND _status > 3 AND _status != 9",
            intval($customerId)
        );
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {
                echo '
                <a href="customerShowOrder.php?id='.$row['_id'].'" class="nostyle">
                    <div class="param">
                        <img src="img/icons/form.png" class="wicon">
                        <div class="sdat">
                            <p class="stitle" id="1m">Zamówienie nr. '.$row['_id'].'</p>
                            <p class="sdetail">Zobacz szczegóły swojego zamówienia</p>
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
		
		<p class="info">Twoje zamówienia oczekujące na oferty</p>
        
        <?php
            //cała reszta
            MakeOrders();
        ?>

        <p class="info">Zamówienia trakcie Realizacji</p>
        
        <?php
            //te z ready to go
            MakeAcceptedOrders();
        ?>

    <p class="info">Zakończone zamówienia</p>
        
        <?php
            //te z accepted
            MakeEndOrders();
        ?>
		
		<div class="spacer"></div>
    </div>
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
	
	<script src="js/general.js"></script>
</BODY>
