<?php
    /* Modify $username, $password and $dbname variable to match your own */
    
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "Marketplace";
    $conn = new mysqli ($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        $conn = new mysqli ($servername, $username, $password, $dbname);
    }

    $createUser = $conn->prepare("INSERT INTO Users (user_firstname, user_lastname, user_email, user_password) 
    VALUES (?, ?, ?, ?)");
    $createUser->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

    $findUser = $conn->prepare("SELECT user_id, user_firstname, user_password FROM Users WHERE user_email=?");
    $findUser->bind_param("s", $email);

    $resetPassword = $conn->prepare("UPDATE Users SET user_password=? WHERE user_id=?");
    $resetPassword->bind_param("si", $hashedPassword, $userId);

    $createItem = $conn->prepare("INSERT INTO Items (item_name, item_description, item_price, item_creator_email) VALUES (?, ?, ?, ?)");
    $createItem->bind_param("ssss", $itemName, $itemDescription, $itemPrice, $email);

    $findItems = $conn->prepare("SELECT item_id, item_name, item_description, item_price FROM Items WHERE item_creator_email=?");
    $findItems->bind_param("s", $email);

    $updateItem = $conn->prepare("UPDATE Items SET item_name=?, item_description=?, item_price=? WHERE item_id=?");
    $updateItem->bind_param("sssi", $updatedItemName, $updatedItemDescription, $updatedItemPrice, $itemId);

    $deleteItem = $conn->prepare("DELETE FROM Items WHERE item_id=?");
    $deleteItem->bind_param("i", $itemId);
?>