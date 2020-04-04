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
        if($_SESSION['drasin'] == 776)
        {
            header("Location: customer.php");
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
            $sql = sprintf("INSERT INTO CUSTOMERS (_email, _password, _name, _surname, _phoneNumber, _city, _street, _homeNumer, _status)
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
    if(isset($_POST['city']))
    {
        if($_POST['city'] == -1)
        {
            echo "<script>";
            echo "alert('Uzupełnij dane');";
            echo "</script>"; 
        }
    }

?>

<html>
<body>
    <form action = "?" method = "POST">
        email<input type = "email" name = "email" placeholder = "EMAIL"> <br />
        imie<input type = "text" name = "name" placeholder = "IMIE"> <br />
        nazwisko<input type = "text" name = "surname" placeholder = "NAZWISKO"> <br />
        numer telefonu<input type = "number" name = "phoneNumber" placeholder = "NR TELEFONU"> <br />
        hasło<input type = "password" name = "password" placeholder = "HASŁO"> <br />
        Miasto: <select name = "city">
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
        </select> <br />
        ulica<input type = "text" name = "street" placeholder = "ULICA"> <br />
        numer domu (w przypadku miszkania podaj numer mieszkania oddzielony przez /):<input type = "text" name = "homeNumber" placeholder = "NUMER DOMU"> <br />
        <input type = "submit" name = "create" value = "Załóż konto"> <br /><br /><br />
    </form>
    <a href = "customerLogin.php"><button>Zaloguj</button></a>
</body>
</html>
