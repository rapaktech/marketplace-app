<?php
    /* Run this file once to seed table into created database.
    Modify $username, $password and $dbname variable to match your own */

    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "Marketplace";

    $conn = new mysqli ($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die ("Connection failed: " . $conn->connect_error);
    }

    $items = "CREATE TABLE Items (
        item_id INT (16) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        item_name VARCHAR(255) NOT NULL,
        item_description VARCHAR(1000) NOT NULL,
        item_time_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        item_last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        item_creator_email varchar(255) NOT NULL
    )";

    if ($conn->query($items) === TRUE) {
        return;
    } else {
        echo $conn->error;
    }


    $users = "CREATE TABLE Users (
        user_id int(16) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_firstname varchar(255) NOT NULL,
        user_lastname varchar(255) NOT NULL,
        user_email varchar(255) NOT NULL UNIQUE KEY,
        user_password varchar(255) NOT NULL,
        user_reg_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        user_last_updated timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        user_enabled tinyint(1) UNSIGNED NOT NULL DEFAULT '1'
    )";

    if ($conn->query($users) === TRUE) {
        return;
    } else {
        echo $conn->error;
    }
?>