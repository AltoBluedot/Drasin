<?php 
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    session_start();
    $_SESSION['drasin'] = NULL;
    $_SESSION['draCId'] = NULL;
    $_SESSION['draSId'] = NULL;
    echo "<script>";
    echo "alert('Wylogowano');";
    echo "location.replace('index.php');";
    echo "</script>";
?>
