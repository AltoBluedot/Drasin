<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    $servername = "";
    $username = "";
    $password = "";
    $dbname = "";


    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("SET NAMES 'utf8mb4'");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

?>
