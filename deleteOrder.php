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
        if($_SESSION['drasin'] != 776){
            header("Location: customerLogin.php");
        }
    }
    else{
        header("Location: customerLogin.php");
    }

    if(!isset($_GET['get'])){
        header("Location: index.php");
    }

    $orderId=(int)$_GET['get'];
    $sql = sprintf("SELECT _id_customer FROM ORDERS WHERE _id=%d",
        intval($orderId)
    );
    $result=$conn->query($sql);
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if($_SESSION['draCId'] = $row['_id_customer']){
            $sql = sprintf("DELETE FROM NEEDS WHERE _id_order=%d",
                intval($orderId)
            );
            if($conn->query($sql)){
                $sql = sprintf("DELETE FROM ORDERS WHERE _id=%d AND _status=1",
                    intval($orderId)
                );
                if($conn->query($sql)){
                    echo "<script>";
                    echo "alert('Nie znaleziono sklepu w twojej lokalizacji ktore posiadaja wszystkie zadane produkty');";
                    echo "location.replace('goshopping.php');";
                    echo "</script>";
                }
            }
        }
    }
    echo "<script>";
    echo "alert('Błąd bazy danych');";
    echo "location.replace('goshopping.php');";
    echo "</script>";
?>
