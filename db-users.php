<?php
/*  Run this file once to seed table into created database.
    Modify $username, $password and $dbname variable to match your own */


    require "dotenv-parser.php";

    use DevCoder\DotEnv;

    (new DotEnv(__DIR__ . '/.env'))->load();



    $servername = getenv("SERVER_NAME");
    $username = getenv("DB_USERNAME");
    $pass = getenv("DB_PASSWORD");
    $dbname = getenv("DB_NAME");
    define('servername', getenv("SERVER_NAME"));
    define('username', getenv("DB_USERNAME"));
    define('pass', getenv("DB_PASSWORD"));
    define('dbname', getenv("DB_NAME"));
    $conn = new mysqli (servername, username, pass, dbname);


    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }


    $users = "CREATE TABLE Users (
        user_num INT (16) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_phone VARCHAR (255) NOT NULL,
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