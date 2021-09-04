<?php
    if (!isset($_COOKIE["jimmarketplaceuser"])) {
        die ("Please login to access this page");
    }
?>



<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-user']) == true) {
        if (!empty($_POST["firstname"])) {
            $testFirstName = test_input($_POST["firstname"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$firstName)) {
                $firstName = false;
                echo "Only letters and white space allowed for First Name field. Please edit and try again.<br>";
            } else {
                $firstName = $testFirstName;
            }
        }

        if (!empty($_POST["lastname"])) {
            $testLastName = test_input($_POST["lastname"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/",$lastName)) {
                $lastName = false;
                echo "Only letters and white space allowed for Last Name field. Please edit and try again.<br>";
            } else {
                $lastName = $testLastName;
            }
        }

        if (!empty($_POST["phone"])) {
            $testPhone = test_input($_POST["phone"]);
            if (!preg_match("/^(?=.*\d)[\d]{8,}$/",$phone)) {
                $phone = false;
                echo "Phone Number must be at least 8 characters and include only numbers.<br>";
            } else {
                $phone = $testPhone;
            }
        }

        if ($firstName && $lastName && $phone) {
            require "db-conn.php";

            foreach ($_COOKIE["jimmarketplaceuser"] as $key => $value) {
                if ($key == "id") {
                    $id = $value;
                } else {
                    continue;
                }
            }

            $result = updateLoggedInUser($id, $firstName, $lastName, $phone);
            
            if ($result === true) {
                setcookie("jimmarketplaceuser[id]", $id, time()+3600, "/", "localhost:4000", false, false);
                setcookie("jimmarketplaceuser[firstname]", $firstName, time()+3600, "/", "localhost:4000", false, false);
                setcookie("jimmarketplaceuser[lastname]", $lastName, time()+3600, "/", "localhost:4000", false, false);
                setcookie("jimmarketplaceuser[phone]", $phone, time()+3600, "/", "localhost:4000", false, false);
                header("location: dashboard.php");
                echo "Update Is Successful";
            } else {
                echo $result;
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dashboard']) == true) {
        header("location: dashboard.php");
    }


    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .error {
            color: #FF0000;
        }

        div {
            padding-top: 50px;
        }
    </style>
    <title>Update Your Profile Information</title>
</head>
<body>

    <div class="update">
        <form action="" method="post">
            <?php
                foreach ($_COOKIE["jimmarketplaceuser"] as $key => $value) {
                    if ($key == "id" || $key == "email") {
                        continue;
                    }
                    else {
                        echo "<label for=\"{$key}\">{$key}: </label>
                        <input type=\"text\" name=\"{$key}\" value=\"{$value}\"><br><br><br><br>";
                    }
                }
            ?>
            <input type="submit" name="update-user" id="update-user" value="Update Your Profile Information">
        </form>
    </div>
    
    <div class="dashboard">
        <form action="" method="post">
            <input type="submit" name="dashboard" id="dashboard" value="Return To Your Dashboard">
        </form>
    </div>
</body>
</html>