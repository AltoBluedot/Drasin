<?php
/*
        Authors:
        Adam Bieńkowski
        Piotr Bieńkowski
        Bartosz Kostarczyk
        Mateusz Mazurczak
*/
    require_once("db.php");
    session_start();
    if(isset($_SESSION['drasin']))
    {
        if($_SESSION['drasin'] == 776)
        {
            header("Location: customer.php");
        }
    }

    if(isset($_POST['log']))
    {
        $email = htmlentities($_POST['email']);
        $password = htmlentities($_POST['password']);

        $sql = sprintf("SELECT * FROM CUSTOMERS WHERE _email = '%s'",
            $conn->real_escape_string($email)
        );

        $result = $conn->query($sql);
        if ($result->num_rows == 0) 
        {
            echo "<script>";
            echo "alert('Podane dane są błędne');";
            echo "</script>";
        }
        else
        {
            $row = $result->fetch_assoc();
            if(password_verify($password, $row['_password']))
            {
                $_SESSION['drasin'] = 776;
                $_SESSION['draCId'] = $row['_id'];
		        $_SESSION['draCcId'] = $row['_id_city'];
                header("Location: customer.php");
            }
            else
            {
                echo "<script>";
                echo "alert('Podane dane są błędne');";
                echo "</script>";
            }
        }
    }

    if(isset($_POST['create']) && $_POST['city'] != -1)
    {
        $email = htmlentities($_POST['email']);
        $name = htmlentities($_POST['name']);
        $surname = htmlentities($_POST['surname']);
        $phoneNumber = htmlentities($_POST['phoneNumber']);
        $password = htmlentities($_POST['password']);
        $city = (int)($_POST['city']);
        $street = htmlentities($_POST['street']);
        $homeNumber = htmlentities($_POST['homeNumber']);

        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = sprintf("SELECT * FROM CUSTOMERS WHERE _email = '%s'", 
            $conn->real_escape_string($email)
        );
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            echo "<script>";
            echo "alert('Konto na podanego mejla już istnieje');";
            echo "</script>"; 
        }
        else
        {
            $sql = sprintf("INSERT INTO CUSTOMERS (_email, _password, _name, _surname, _phoneNumber, _id_city, _street, _homeNumer, _status)
            VALUES ('%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', 1)",
                $conn->real_escape_string($email),
                $conn->real_escape_string($password),
                $conn->real_escape_string($name),
                $conn->real_escape_string($surname),
                $conn->real_escape_string($phoneNumber),
                intval($city),
                $conn->real_escape_string($street),
                $conn->real_escape_string($homeNumber)
            );
    
            if ($conn->query($sql) === TRUE) 
            {
                echo "<script>";
                echo "alert('Konto zostało dodane');";
                echo "location.replace('customerLogin.php');";
                echo "</script>";
            } 
            else 
            {
                echo "<script>";
                echo "alert('Coś poszło nie tak, spróbuj ponownie później');";
                echo "</script>";
            }
        }
    }
    if(isset($_POST['create']) && isset($_POST['city']))
    {
        if($_POST['city'] == -1)
        {
            echo "<script>";
            echo "alert('Uzupełnij dane');";
            echo "</script>"; 
        }
    }

?>

<!DOCTYPE HTML>
<HEAD>
	<TITLE>Drasin</TITLE>
	
	<link href="img/logos/none-set" rel="icon">
	<link href="css/login.css" rel="stylesheet">
	
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
		
		setTimeout(function() {
			body.style.opacity = 1;
		}, 0);
	</script>
	
	<div class="midbox" id="1">
		<img src="img/logos/Drasin-Logo.png" id="brand">
		
		<input id="log" class="okform" style="background: black; color: white;" type="submit" value="Logowanie">
		<input id="rej" class="okform" type="submit" value="Rejestracja">
	</div>
	
	<div class="midbox noview" id="2">
		<img src="img/logos/Drasin-Logo.png" id="brand">
		<form action="?" method="post">
            Zaloguj się jako Klient:
			<input required class="txtin" name="email"    type="text"     placeholder="Login" autocomplete="off">
			<input required class="txtin" name="password" type="password" placeholder="Hasło" autocomplete="off">
			
			<input class="okform" type="submit" value="Zaloguj" name = "log">
		</form>
	</div>
        
        
      
	<div class="midbox noview" id="3">
		<img src="img/logos/Drasin-Logo.png" id="brand">
		<form action="?" method = "POST">
            Załóż konto jako Klient:
			<input required class="txtin" name="email"     type="email"     placeholder="E-mail"        autocomplete="off">
			<input required class="txtin" name="name"     type="text"     placeholder="Imie"        autocomplete="off">
			<input required class="txtin" name="surname"     type="text"     placeholder="Nazwisko"        autocomplete="off">
			<input required class="txtin" type = "number" name = "phoneNumber" placeholder = "Nr telefonu"  autocomplete="off">
            <select class="txtin" name = "city">
            <option value = "-1">Wybierz miasto</option>
            <?php
                $sql = "SELECT * FROM CITIES";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "<option value = '".$row['_id']."'>".$row['_name']."</option>";
                    }
                }
            ?>
            </select>
            <input required class="txtin" type = "text" name = "street" placeholder = "Ulica">
            <input required class="txtin" type = "text" name = "homeNumber" placeholder = "Numer domu">
            <input required class="txtin" name="password"  type="password" placeholder="Hasło"         autocomplete="off">
			
			<input class="okform" type="submit" name = "create" value="Zarejestruj">
		</form>
	</div>
	
	<script src="js/general.js"></script>
</BODY>
