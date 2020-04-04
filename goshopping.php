<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    require("db.php");
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

    $customerId = $_SESSION['draCId'];

    $sql = sprintf("SELECT * FROM CUSTOMERS WHERE _id = %d",
        intval($customerId)
    );
    $result = $conn->query($sql);
    if ($result->num_rows == 0) 
    {
        header("Location: customer.php");
    }
    $row = $result->fetch_assoc();
    $name = $row['_name'];
    $surname = $row['_surname'];
    $email = $row['_email'];
    $phoneNumber = $row['_phoneNumber'];
    $homeNumber = $row['_homeNumer'];
    $street = $row['_street'];

    if(isset($_POST['order'])){
        //zbierz zamówienie z POST-a i wyślij do bazy danych 
        $orderId=0;
        $bool = 1;
        $ROWS=$_SESSION['rows'];
        for($i=0;$i<$ROWS;++$i){
             $productId=$_POST['r'.$i];
             $n='p'.$productId;
             if($_POST[$n]>0){
                if($bool){
                    //stworz zamowienie
                    $sql = sprintf("INSERT INTO ORDERS (_id_customer, _id_city, _status, _street, _homeNumber, _phoneNumber, _name, _surname, _email) 
                    VALUES (%d, %d, 1, '%s', '%s', '%s', '%s', '%s', '%s')",
                        intval($_SESSION['draCId']),
                        intval($_SESSION['draCcId']),
                        $conn->real_escape_string($street),
                        $conn->real_escape_string($homeNumber),
                        $conn->real_escape_string($phoneNumber),
                        $conn->real_escape_string($name),
                        $conn->real_escape_string($surname),
                        $conn->real_escape_string($email)
                    );
                    if ($conn->query($sql) === TRUE) {
                        $sql = "SELECT * FROM ORDERS ORDER BY _id DESC LIMIT 1";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) 
                        {
                            $row = $result->fetch_assoc();
                            $orderId = $row['_id'];
                        }
                        $bool=0;
                    }
                    else{
                        echo "error";
                    }
                }
                $sql = sprintf("INSERT INTO NEEDS (_id_order, _id_product, _amount) VALUES (%d, %d, %d)",
                    intval($orderId),
                    intval($productId),
                    intval($_POST[$n])
                );
                if ($conn->query($sql) === TRUE) {
                }
                else{
                    echo "error";
                }
                //dodaj produkt do zamówienia
             }
        }
        unset($_POST['order']);
        header("Location: connectshops.php?get=$orderId");
    }

    function displayProducts(){
        require("db.php");
        //pobierz listę produktów i jednostki (ceny, jeśli robimy system bezkonkurencyjny
        $sql = "SELECT * FROM PRODUCTS ORDER BY _name";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $R=$result->num_rows;
            $_SESSION['rows']=$R;
            $i = 0;
            while($row = $result->fetch_assoc()) 
            {
                echo '
                    <p class="tx1">'.$row['_name'].' ('.$row['_unit'].')</p>
                    <input class="amounts" type="number" name="p'.$row['_id'].'" value="0">
                    <input class="amounts" type="hidden" name="r'.$i.'" value="'.$row['_id'].'">
                    <br>
                ';
                $i++;
            }
        }
    }
?>
<!DOCTYPE HTML>
<HEAD>
    <TITLE>Drasin</TITLE>

    <link href="img/logos/Pulsar.png" rel="icon">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/jquery.js"></script>

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

        setTimeout(function () {
            body.style.opacity = 1;
        }, 0);
    </script>

    <div class="weatherinfo" id="sli">
        <div class="appinfo">
            <img src="img/logos/Drasin-Logo.png" style="margin-bottom: 20px;" draggable="false" class="appico">
        </div>

        <form action="?" method="post">
            <p class="infos">Powiedz nam czego ci potrzeba.</p>
            <?php
                displayProducts();
            ?>
            <br />
            <input type="submit" value="Zamów" name = "order" class="mainbut">
        </form>

        <div class="spacer"></div>
    </div>

    <script src="js/general.js"></script>
</BODY>
