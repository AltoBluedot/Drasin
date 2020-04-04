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
        if($_SESSION['drasin'] != 667)
        {
            header("Location: shopLogin.php");
        }
    }
    else
    {
        header("Location: shopLogin.php");
    }

    if(!isset($_GET['get']))
    {
        header("Location: shop.php?info=err");
    }
    $readyId=(int)$_GET['get'];

    if(isset($_POST['accept'])){
        $sql = sprintf("SELECT _id_order FROM READYTOGO WHERE _id = %d",
            intval($readyId)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $orderId = $row['_id_order'];

            $sql = sprintf("UPDATE ORDERS SET _status=3 WHERE _id=%d",
                intval($orderId)
            );
            if ($conn->query($sql) === TRUE) {
                $sql = sprintf("UPDATE READYTOGO SET _status=3 WHERE _id=%d",
                    intval($readyId)
                );
                if ($conn->query($sql) === TRUE) {
                    header("Location: activeOrders.php?info=accepted");
                }
                else{
                    echo "errorB,";
                }
            }
            else{
                echo "errorB,";
            }
        }
    }
    else if(isset($_POST['discard'])){
        $sql = sprintf("SELECT _id_order FROM READYTOGO WHERE _id = %d",
            intval($readyId)
        );
        $result = $conn->query($sql);
        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $orderId = $row['_id_order'];

            $sql = sprintf("DELETE FROM READYTOGO WHERE _id=%d",
                intval($readyId)
            );
            if ($conn->query($sql) === TRUE){
                $sql = sprintf("SELECT _id FROM READYTOGO WHERE _id_order = %d",
                    intval($orderId)
                );
                $result = $conn->query($sql);
                if ($result->num_rows == 0) 
                {
                    $sql = sprintf("UPDATE ORDERS SET _status=0 WHERE _id=%d",
                        intval($orderId)
                    );
                    if ($conn->query($sql) === TRUE){
                
                        header("Location: activeOrders.php?info1=discarded");
                    }
                    else{
                        echo "errorB,";
                    }
                }
                else
                {
                    header("Location: activeOrders.php?info2=discarded");
                }
            }
            else{
                echo "errorB,";
            }
        }
    }
    else{
        header("Location: shopLogin.php");
    }
?>
