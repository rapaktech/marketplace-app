<?php
/* Run this file once to seed table into created database.
    Modify $username, $password and $dbname variable to match your own */

    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "Marketplace";

    $conn = new mysqli ($servername, $username, $password, $dbname);


$users = "CREATE TABLE Users (
    user_num INT (16) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_firstname VARCHAR (255) NOT NULL,
    user_lastname VARCHAR (255) NOT NULL,
    user_email VARCHAR (255) NOT NULL UNIQUE KEY,
    user_password VARCHAR(255) NOT NULL,
    user_reg_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    user_enabled TINYINT (1) NOT NULL DEFAULT '1'
)";

if ($conn->query($users) === TRUE) {
    echo "Table Users Created Successfully<br>";
} else {
    echo $conn->error;
}

?>