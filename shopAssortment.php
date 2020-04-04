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
        if($_SESSION['drasin'] != 667)
        {
            header("Location: shopLogin.php");
        }
    }
    else
    {
        header("Location: shopLogin.php");

    }
    
    function arraySearch($array, $value)
    {
        for($i = 0; $i < sizeof($array); $i++)
        {
            if($array[$i] == $value)
            {
                return $i;
            }
        }
        return -1;
    }

    $shopId = $_SESSION['draSId'];

    $checkedAssortment[0] = -1;
    $checkedAssortmentPrice[0] = -1;
    $sql = sprintf("SELECT * FROM ASSORTMENT WHERE _shopId = %d",
        intval($shopId)
    );
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        $i = 0;
        while($row = $result->fetch_assoc())
        {
            $checkedAssortment[$i] = $row['_productId'];
            $checkedAssortmentPrice[$i] = $row['_price'];
            $i++;
        }
    }
    
    function makeProducts($checkedAssortment, $checkedAssortmentPrice)
    {
        include "db.php";
        $sql = "SELECT * FROM PRODUCTS ORDER BY _name";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $i = 0;
            while($row = $result->fetch_assoc()) 
            {
                echo '
                <label class="container">
                    '.$row['_name'].' ('.$row['_unit'].') 
                    <input type="checkbox" ';
                    if(arraySearch($checkedAssortment, $row['_id']) != -1)
                    {
                        echo "checked";
                    }
                    echo ' name="c'.$i.'" value="'.$row['_id'].'">
                    <span class="checkmark"></span>
                </label>
                <input type = "text" class="amounts" name = "i'.$i.'" placeholder = "cena" value = "';
                if(arraySearch($checkedAssortment, $row['_id']) != -1)
                {
                    echo $checkedAssortmentPrice[arraySearch($checkedAssortment, $row['_id'])];
                }
                echo '"><br>';
                $_SESSION['assortmentArray'][$i] = $row['_id'];
                $i++;
            }
        }
        $_SESSION['assortment'] = $i;
    }

    if(isset($_POST['save']))
    {
        $toAdd[0] = -1;
        $toAddPrice[0] = -1;
        $toUpdate[0] = -1;
        $toUpdatePrice[0] = -1;
        $toDelete[0] = -1;
        $n = (int)($_SESSION['assortment']);
        for($i = 0; $i < $n; $i++)
        {
            if(isset($_POST['c'.strval($i)]))
            {
                
                $value = $_POST['c'.strval($i)];
                $price = floatval($_POST['i'.strval($i)]);
                if(arraySearch($checkedAssortment, $value) == -1)
                {
                    $toAdd[sizeof($toAdd)] = $value;
                    $toAddPrice[sizeof($toAddPrice)] = $price;
                }
                else
                {
                    if($price != $checkedAssortmentPrice[arraySearch($checkedAssortment, $value)])
                    {
                        $toUpdate[sizeof($toUpdate)] = $value;
                        $toUpdatePrice[sizeof($toUpdatePrice)] = $price;
                    }
                }
            }
            else
            {
                $value = $_SESSION['assortmentArray'][$i];
                if(arraySearch($checkedAssortment, $value) != -1)
                {
                    $toDelete[sizeof($toDelete)] = $value;
                }
            }
        }

        for($i = 1; $i < sizeof($toAdd); $i++)
        {
            //echo $toAdd[$i]." ".$toAddPrice[$i]."<br />";
            $temp = $toAddPrice[$i];
            $sql = sprintf("INSERT INTO ASSORTMENT (_shopId, _productId, _price)
            VALUES (%d, %d, $temp)",
                intval($shopId),
                intval($toAdd[$i]),
            );
            if ($conn->query($sql) === TRUE) {
            }
        }
        
        for($i = 1; $i < sizeof($toDelete); $i++)
        {
            //echo $toDelete[$i]."<br />";
            $sql = sprintf("DELETE FROM ASSORTMENT WHERE _shopId = %d AND _productId = %d",
                intval($shopId),
                intval($toDelete[$i])
            );

            if ($conn->query($sql) === TRUE) {
            } 

        }

        for($i = 1; $i < sizeof($toUpdate); $i++)
        {
            //echo $toUpdate[$i]." ".$toUpdatePrice[$i]."<br />";
            $temp = $toUpdatePrice[$i];
            $sql = sprintf("UPDATE ASSORTMENT SET _price = $temp WHERE _shopId = %d AND _productId = %d",
                intval($shopId),
                intval($toUpdate[$i])
            );

            if ($conn->query($sql) === TRUE) {
            }
            
        }
        header("Location: shopAssortment.php");
    }

    

?>

<!DOCTYPE HTML>
<HEAD>
    <TITLE>Drasin</TITLE>

    <link href="img/logos/favicon.png" rel="icon">
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
            <p class="infos">Powiedz nam, jakie produkty są dostępne.</p>
            <?php
                makeProducts($checkedAssortment, $checkedAssortmentPrice);
            ?>
            <br />
            
            <input type="submit" value="Zapisz" name = "save" class="mainbut">
        </form>

        <div class="spacer"></div>
    </div>

    <script src="js/general.js"></script>
</BODY>
