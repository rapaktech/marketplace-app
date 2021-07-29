<?php
    /* Run this file once to seed table into created database.
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

    $items = "CREATE TABLE Items (
        item_id INT (16) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR (255) NOT NULL,
        item_description VARCHAR (1000) NOT NULL,
        item_price INT (16) NOT NULL,
        item_time_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        item_last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        item_creator_email VARCHAR (255) NOT NULL
    )";

    if ($conn->query($items) === TRUE) {
        echo "Table Items Created Successfully<br>";
    } else {
        echo $conn->error;
    }
    
?>