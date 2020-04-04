<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    session_start();
    require_once("db.php");
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
        header("Location: customer.php");
    }
    $readyId = (int)($_GET['id']);
    $customerId = $_SESSION['draCId'];
    $sql = sprintf("SELECT * FROM READYTOGO WHERE _id = %d AND _status = 3",
        intval($readyId)
    );
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        $row = $result->fetch_assoc();
        $orderId=$row['_id_order'];
        $shopId=$row['_id_shop'];
        $price=$row['_price'];
    }
    else{
        header("Location: customerShowOrders.php");
    }


    $sql = sprintf("SELECT * FROM ORDERS WHERE _id = %d AND _id_customer = %d",
        intval($orderId),
        intval($customerId)
    );
    $result = $conn->query($sql);
    if ($result->num_rows == 0) 
    {
        header("Location: customerShowOrders.php");
    }
    $row = $result->fetch_assoc();
    $name = $row['_name'];
    $surname = $row['_surname'];
    $street = $row['_street'];
    $homeNumber = $row['_homeNumber'];
    $city = $row['_id_city'];
    $email = $row['_email'];
    $phoneNumber = $row['_phoneNumber'];
    $status = $row['_status'];

    $sql = sprintf("SELECT * FROM CITIES WHERE _id = %d",
        intval($city)
    );
    $result = $conn->query($sql);
    if ($result->num_rows == 0) 
    {
        header("Location: customer.php");
    }
    $row = $result->fetch_assoc();
    $cityName = $row['_name'];

    $products[0] = -1;
    $pNumer[0] = -1;
    $sql = sprintf("SELECT * FROM NEEDS WHERE _id_order = %d",
        intval($orderId)
    );
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        $i = 0;
        while($row = $result->fetch_assoc()) 
        {
            $products[$i] = (int)($row['_id_product']);
            $pNumber[$i] = (int)$row['_amount'];
            $i++;
        }
    }

    if($status >= 4)
    {
        $sql = sprintf("SELECT * FROM ACCEPTED WHERE _orderId = %d",
        intval($id)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $shopId = $row['_shopId'];
            $price = $row['_price'];
            $sql = sprintf("SELECT * FROM SHOPS WHERE _id = %d",
            intval($shopId)
            );
            $result = $conn->query($sql);
            if ($result->num_rows > 0) 
            {
                $row = $result->fetch_assoc();
                $shopName = $row['_name'];
                $shopPhoneNumber = $row['_phoneNumber'];
                $shopEmail = $row['_email'];
                $shopBankNumber = $row['_bankNumber'];
            }
        }
    }

    if(isset($_POST['caccept'])){
        $sql = sprintf("UPDATE ORDERS SET _status=4 WHERE _id=%d",
            intval($orderId)
        );
        if ($conn->query($sql) === TRUE) {
            $sql = sprintf("INSERT INTO ACCEPTED( _customerId, _shopId, _orderId, _price, _end) VALUES (%d, %d, %d, %f, 1)",
                intval($customerId),
                intval($shopId),
                intval($orderId),
                floatval($price)
            );
            if ($conn->query($sql) === TRUE) {
                $sql = sprintf("DELETE FROM READYTOGO WHERE _id_order=%d",
                    intval($orderId)
                );
                if ($conn->query($sql) === TRUE) {
                    header("Location: customer.php");
                }
                else{
                    echo "errorB3,";
                }
            }
            else{
                echo "errorB2,";
                echo $conn->error;
            }
        }
        else{
            echo "errorB,";
        }
    }
    else if(isset($_POST['cdiscard'])){
        $sql = sprintf("DELETE FROM READYTOGO WHERE _id=%d",
            intval($readyId)
        );
        if ($conn->query($sql) === TRUE) {
            header("Location: customer.php?=discarded");
        }
        else{
            echo "errorB3,";
        }
    }
?>

<!DOCTYPE HTML>
<HEAD>
	<TITLE>Drasin</TITLE>
	
	<link href="img/logos/favicon.png" rel="icon">
	<link href="css/style.css" rel="stylesheet">
	
	<link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700&display=swap" rel="stylesheet">
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	
</HEAD>
<BODY id="main" style="border-radius: 10px;">
    <div class="appinfo">
        <img src="img/logos/Drasin-Logo.png" draggable="false" class="appico">
    </div>
    <br />
	<div id="oldbrowser" style="display: none;"></div>
    <form action="customerShopOffer.php?id=<?php echo"$readyId" ?>" method = "post">
        <p class="ina">Czy wybierasz ten sklep, aby zrealizował twoje zamówienie?</p> 	
		<input class="taform" type="submit" name = "caccept" value="">
        <input class="neform" type="submit" name = "cdiscard" value="">
	</form>
    <h1>Zamówienie nr. <?php echo $orderId; ?></h1>
        <b>Cena zamówienia:</b> <?php echo $price; ?> zł <br />
    <h2>Zamówienie:</h2>
    <b>Imie:</b> <?php echo $name; ?><br /><br />
    <b>Nazwisko:</b> <?php echo $surname; ?><br /><br />
    <b>Adres:</b> <?php echo $street; ?>, <?php echo $homeNumber ?>, <?php echo $cityName; ?><br /><br />
    <b>Numer telefonu:</b> <?php echo $phoneNumber ?><br /><br />
    <b>E-mail:</b> <?php echo $email;?><br /><br />

    <h2>Produkty:</h2>
    <?php
        for($i = 0; $i < sizeof($products); $i++)
        {
            $temp = $products[$i];
            $sql = sprintf("SELECT * FROM PRODUCTS WHERE _id = %d",
                $temp
            );
            $result = $conn->query($sql);
            if ($result->num_rows > 0) 
            {
                $row = $result->fetch_assoc();
                echo "<b>".($i + 1).")</b> ".$row['_name'].", (".$pNumber[$i]." ".$row['_unit'].")";
                echo "<br /><br />";
            }
        }
    ?>

	
	
</BODY>
