<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    //pokazanie ofert
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

    if(!isset($_GET['id']))
    {
        header("Location: customerShopOrdersOffers.php");
    }

    $id = (int)($_GET['id']);

    function MakeOrders()
    {
        require_once("db.php");
        $customerId = $_SESSION['draCId'];
        $id = (int)($_GET['id']);

        $sql = sprintf("SELECT * FROM READYTOGO WHERE _status = 3 AND _id_order = %d",
            intval($id)
        );
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc()) 
            {
                $shopId = $row['_id_shop'];
                $sql2 = sprintf("SELECT * FROM SHOPS WHERE _id = %d",
                    intval($shopId)
                );
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) 
                {
                    $row2 = $result2->fetch_assoc();
                    $shopName = $row2['_name'];
                }
                
                echo '
                <a href="customerShopOffer.php?id='.$row['_id'].'" class="nostyle">
                    <div class="param">
                        <img src="img/icons/form.png" class="wicon">
                        <div class="sdat">
                            <p class="stitle" id="1m">Oferta ('.$shopName.') - '.$row['_price'].'zł</p>
                            <p class="sdetail">Zobacz ofertę zamówienia</p>
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
		
		<p class="info">Twoje zamówienia czekające na ofrty</p>
        
        <?php
            MakeOrders();
        ?>
		
		<div class="spacer"></div>
	</div>
	
	<script src="js/general.js"></script>
</BODY>

