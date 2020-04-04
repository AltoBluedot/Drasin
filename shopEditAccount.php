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
        if($_SESSION['drasin'] != 667)
        {
            header("Location: shopLogin.php");
        }
    }
    else
    {
        header("Location: shopLogin.php");

    }

    $shopId = $_SESSION['draSId'];
    
    $sql = sprintf("SELECT * FROM SHOPS WHERE _id = %d",
        intval($shopId)
    );

    $result = $conn->query($sql);
    if ($result->num_rows == 0) 
    {
        header("Location: shop.php");
    }
    $row = $result->fetch_assoc();
    $email = $row['_email'];
    $name = $row['_name'];
    $phoneNumber = $row['_phoneNumber'];
    $passwordOld = $row['_password'];
    $city = $row['_city'];
    $bankNumber = $row['_bankNumber'];

    $sql = sprintf("SELECT * FROM CITIES WHERE _id = %d",
        intval($city)
    );
    $result = $conn->query($sql);
    if ($result->num_rows == 0) 
    {
        header("Location: shop.php");
    }
    $row = $result->fetch_assoc();
    $cityName = $row['_name'];

    if(isset($_POST['create']) && $_POST['city'] != -1)
    {
        $email = htmlentities($_POST['email']);
        $name = htmlentities($_POST['name']);
        $phoneNumber = htmlentities($_POST['phoneNumber']);
        $city = (int)($_POST['city']);
        $bankNumber = htmlentities($_POST['bankNumber']);

        $sql = sprintf("SELECT * FROM SHOPS WHERE _email = '%s'", 
            $conn->real_escape_string($email),
            intval($shopId)

        );
        $result = $conn->query($sql);
        if ($result->num_rows > 1) 
        {
            echo "<script>";
            echo "alert('Konto na podanego mejla już istnieje');";
            echo "</script>"; 
        }
        else
        {
            $sql = sprintf("UPDATE SHOPS SET _email = '%s', _name = '%s', _phoneNumber = '%s', _city = %d, _bankNumber = '%s' WHERE _id = $shopId", 
                $conn->real_escape_string($email),
                $conn->real_escape_string($name),
                $conn->real_escape_string($phoneNumber),
                intval($city),
                $conn->real_escape_string($bankNumber)
            );

    
            if ($conn->query($sql) === TRUE) 
            {
                echo "<script>";
                echo "alert('Zmiany zostały zapisane');";
                echo "location.replace('shop.php');";
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
    if(isset($_POST['city']) && isset($_POST['create']))
    {
        if($_POST['city'] == -1)
        {
            echo "<script>";
            echo "alert('Uzupełnij dane');";
            echo "</script>"; 
        }
    }

    if(isset($_POST['changePassword']))
    {
        $password = htmlentities($_POST['password']);
        $passwordOld2 = htmlentities($_POST['passwordOld']);
        if(password_verify($passwordOld2, $passwordOld))
        {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = sprintf("UPDATE SHOPS SET _password = '%s' WHERE _id = %d",
                $conn->real_escape_string($password),
                intval($shopId)
            );

            if ($conn->query($sql) === TRUE) 
            {
                echo "<script>";
                echo "alert('Hasło zostało zmienione');";
                echo "location.replace('shop.php');";
                echo "</script>";
            } 
            else 
            {
                echo "<script>";
                echo "alert('Coś poszło nie tak, spróbuj ponownie później');";
                echo "</script>";
            }
        }
        else
        {
            echo "<script>";
            echo "alert('Podane hasło nie jest prawidłowe');";
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
</HEAD>
<BODY id="main" style="border-radius: 10px;">
<div class="midbox">
		<img src="img/logos/Drasin-Logo.png" id="brand">
        <br />
        Zmień konto:<br />
        <form action="?" method = "POST">

			<input required class = "txtin" name = "email" type="email" placeholder="E-mail" value = "<?php echo $email; ?>">
			<input required class = "txtin" name = "name" type="text" placeholder="Nazwa sklepu" value = "<?php echo $name; ?>">
			<input required class = "txtin" type = "number" name = "phoneNumber" placeholder = "Nr telefonu" value = "<?php echo $phoneNumber; ?>">
            <input required class = "txtin" type = "text" name = "bankNumber" placeholder = "Nr konta bankowego" value = "<?php echo $bankNumber; ?>">
            <select class="txtin" name = "city">
            <option value = "<?php echo $city; ?>"><?php echo $cityName; ?></option>
            <?php
                $sql = sprintf("SELECT * FROM CITIES WHERE _id != %d",
                    intval($city)
                );
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "<option value = '".$row['_id']."'>".$row['_name']."</option>";
                    }
                }
            ?>
            </select>
            
			<input class="okform" type="submit" name = "create" value="Zapisz zmiany">
        </form>
        <br />
        <br />
        Zmień hasło:
        <form action = "?" method = "POST">
            <input required class = "txtin" name = "passwordOld" type="password" placeholder="Stare hasło">
            <input required class = "txtin" name = "password" type="password" placeholder="Nowe hasło">
            <input class="okform" type="submit" name = "changePassword" value="Zmień hasło">
        </form>
    </div>
</BODY>
