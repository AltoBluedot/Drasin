<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    //pokazanie zamówień i linkowanie do pokazania ofert
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
        require_once("db.php");
        $customerId = $_SESSION['draCId'];

        $sql = sprintf("SELECT * FROM READYTOGO WHERE _status = 3");
        
        $orders[0] = -1;

        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $i = 0;
            while($row = $result->fetch_assoc()) 
            {
                $temp2 = $row['_id_order'];
                $sql2 = sprintf("SELECT * FROM ORDERS WHERE _id = $temp2",
                    intval($customerId)
                );
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();
                $tempId = $row2['_id'];

                $boolTest = true;
                for($j = 0; $j < sizeof($orders); $j++)
                {
                    if($orders[$j] == $tempId)
                    {
                        $boolTest = false;
                        break;
                    }
                }
                
                if($boolTest)
                {
                    $orders[$i] = $tempId;
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) 
                    {
                        echo '
                        <a href="customerShopOffers.php?id='.$row['_id_order'].'" class="nostyle">
                            <div class="param">
                                <img src="img/icons/form.png" class="wicon">
                                <div class="sdat">
                                    <p class="stitle" id="1m">Oferty zamówienia nr. '.$row['_id_order'].'</p>
                                    <p class="sdetail">Zobacz oferty zamówienia</p>
                                </div>
                            </div>
                        </a>
                        ';
                    }
                    $i++;
                }
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
