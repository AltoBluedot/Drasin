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
            header("Location: customer.php?kk=2");
        }
    }
    else
    {
        echo"LOOOL";
        header("Location: customer.php?kk=3");
    }
    if(!isset($_GET['get'])){
        echo "<script>";
        echo "location.replace('index.php');";
        echo "</script>";
    }
    $orderId=(int)$_GET['get'];
    $sql = sprintf("SELECT _id_city FROM ORDERS WHERE _id=%d AND _status=1",
        intval($orderId)
    );
    $result = $conn->query($sql);
    if ($result->num_rows > 0) 
    {
        $orderData=$result->fetch_assoc();
        $city=$orderData['_id_city'];
        $m=0;
        $sql = sprintf("SELECT * FROM NEEDS WHERE _id_order=%d  ORDER BY _id",
            intval($orderId)
        );
        $result2 = $conn->query($sql);
        if ($result2->num_rows > 0) 
        {
            $R=$result2->num_rows;
            $_SESSION['rows']=$R;
            $i = 0;
            while($row = $result2->fetch_assoc()) 
            {
                $prod=$row['_id_product'];
                $sql = sprintf("SELECT * FROM ASSORTMENT, SHOPS WHERE ASSORTMENT._productId=%d AND SHOPS._city=%d AND ASSORTMENT._shopId=SHOPS._id ORDER BY ASSORTMENT._shopId",
                    intval($row['_id_product']),
                    intval($city)
                );
                $result3 = $conn->query($sql);
                if ($result3->num_rows > 0) 
                {
                    $j = 0;
                    while($comp = $result3->fetch_assoc())
                    {
                        $cc=$comp['_shopId'];
                        if(!$i){
                            $shops[$m]=$comp['_shopId'];
                            $tab[$comp['_shopId']]=1;
                            $m++;
                        }
                        else if($tab[$comp['_shopId']]){
                            $tab2[$comp['_shopId']]=1;
                        }
                        $j++;
                    }
                }
                else{
                    echo "Nie udało się znalezc sklepów dla podanych kryteriów";
                    echo "<script>";
                    echo "location.replace('deleteOrder.php?get=$orderId');";
                    echo "</script>";
                }
                if($i){
                    for($k=0;$k<$m;++$k){
                        $tab[$shops[$k]]=$tab2[$shops[$k]];
                    }
                }
                $i++;
            }
        }
        else{
            echo "nie ma takiego zamówienia2";
        }
    }
    else{
        echo "już rozesłaliśmy maile do sklepów w sprawie tego zamówienia";
    }

    function countCost($rtgId)
    {
        require("db.php");
        $sum = 0;
        $sql = sprintf("SELECT * FROM READYTOGO WHERE _id = %d",
            intval($rtgId)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $orderId = $row['_id_order'];
            $shopId = $row['_id_shop'];
            $products[0] = -1;
            $pNumber[0] = -1;

            $sql = "SELECT * FROM NEEDS WHERE _id_order = $orderId";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) 
            {
                $i = 0;
                while($row = $result->fetch_assoc()) 
                {
                    $products[$i] = $row['_id_product'];
                    $pNumber[$i] = $row['_amount'];
                    $i++;
                }
                
                for($i = 0; $i < sizeof($products); $i++)
                {
                    $temp = $products[$i];
                    $sql = "SELECT * FROM ASSORTMENT WHERE _productId = $temp AND _shopId = $shopId";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) 
                    {
                        $row = $result->fetch_assoc();
                        $tempPrice = $row['_price'];
                        $sum += $tempPrice * $pNumber[$i];
                    }
                }
                return $sum;
            }
        }
    }
    $zlicz=0;
    for($k=0;$k<$m;++$k){
        $show1=$shops[$k];
        $show2=$tab[$shops[$k]];
        if($show2)
        {
            $zlicz++;

            $sql = sprintf("INSERT INTO READYTOGO (_id_shop, _id_order, _status) VALUES (%d, %d, 2)",
                intval($show1),
                intval($orderId)
            );
            if ($conn->query($sql) === TRUE) {
                $sql = sprintf("SELECT _id FROM READYTOGO ORDER BY _id DESC LIMIT 1");
                $result4 = $conn->query($sql);
                if ($result4->num_rows > 0) {
                    $row = $result4->fetch_assoc();
                    $readyId = $row['_id'];
                    $cost=countCost($readyId);
                    $sql = sprintf("UPDATE READYTOGO SET _price=%f WHERE _id=%d",
                        floatval($cost),
                        intval($readyId)
                    );
                    if ($conn->query($sql) === TRUE) {
                        echo"succes,";
                    }
                    else{
                        echo "errorB,";
                    }
                }
                else{
                    echo "errorB2,";
                }
            }
            else{
                echo "errorB1,";
            }
        }
    }
    if(!$zlicz){
        echo "<script>";
        echo "location.replace('deleteOrder.php?get=$orderId');";
        echo "</script>";
    }
    else{
        $sql = sprintf("UPDATE ORDERS SET _status=2 WHERE _id=%d",
            intval($orderId)
        );
        if ($conn->query($sql) === TRUE) {
            echo "<script>";
            echo "alert('Dodano zamówienie');";
            echo "location.replace('customer.php?info=succes');";
            echo "</script>";
        }
        else{
            echo "error";
        }
    }
?>
