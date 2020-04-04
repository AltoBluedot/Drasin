<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    $servername = "sql.server106109.nazwa.pl";
    $username = "server106109_drasin";
    $password = "Szuberwlustrze123";
    $dbname = "server106109_drasin";


    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->query("SET NAMES 'utf8mb4'");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

?>
