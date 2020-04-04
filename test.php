<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
session_start();
function countCost($rtgId)
    {
        require_once("db.php");
        $sum = 0.0;
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
                        $sum += floatval($tempPrice * $pNumber[$i]);
                    }
                }
                return floatval($sum);
            }
        }
    }
    
    echo countCost(4);
?>
