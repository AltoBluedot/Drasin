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
        header("Location: customerShowOrders.php");
    }
    $id = (int)($_GET['id']);
    $customerId = $_SESSION['draCId'];
    $sql = sprintf("SELECT * FROM ORDERS WHERE _id = %d AND _id_customer = %d",
        intval($id),
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
        header("Location: shop.php");
    }
    $row = $result->fetch_assoc();
    $cityName = $row['_name'];

    $products[0] = -1;
    $pNumer[0] = -1;
    $sql = sprintf("SELECT * FROM NEEDS WHERE _id_order = %d",
        intval($id)
    );
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        $i = 0;
        while($row = $result->fetch_assoc()) 
        {
            $products[$i] = (int)($row['_id_product']);
            $pNumer[$i] = $row['_amount'];
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
<BODY id="main" style="border-radius: 10px;" >
    <div class="appinfo">
        <img src="img/logos/Drasin-Logo.png" draggable="false" class="appico">
    </div>
    <br />
	<div id="oldbrowser" style="display: none;"></div>
    <h1>Zamówienie nr. <?php echo $id; ?></h1>

    <?php
        if($status >= 4)
        {
            ?>
                <b>Cena zamówienia:</b> <?php echo $price; ?> zł
                <h2>Sklep:</h2>
                <b>Nazwa:</b> <?php echo $shopName; ?> <br /><br />
                <b>Numer Telefonu:</b> <?php echo $shopPhoneNumber; ?> <br /><br />
                <b>Email:</b> <?php echo $shopEmail; ?> <br /><br />
                <b>Numer konta bankowego:</b> <?php echo $shopBankNumber; ?> <br /><br />
            <?php
        }
    

    ?>
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
                echo "<b>".($i + 1).")</b> ".$row['_name'].", (".$pNumer[$i]." ".$row['_unit'].")<br /><br />";
            }
        }
    ?>
<br />
<br />
<br />
<br />
<br />
	
	
</BODY>
