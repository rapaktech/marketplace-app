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

    $updateFirstName = $conn->prepare("UPDATE Users SET user_firstname=? WHERE user_id=?");
    $updateFirstName->bind_param("si", $updatedFirstName, $userId);

    $updateLastName = $conn->prepare("UPDATE Users SET user_lastname=? WHERE user_id=?");
    $updateLastName->bind_param("si", $updatedLastName, $userId);

    $updateEmail = $conn->prepare("UPDATE Users SET user_email=? WHERE user_id=?");
    $updateEmail->bind_param("si", $updatedEmail, $userId);

    $resetPassword = $conn->prepare("UPDATE Users SET user_password=? WHERE user_email=?");
    $resetPassword->bind_param("ss", $newPassword, $email);

    $createItem = $conn->prepare("INSERT INTO Items (item_name, item_description, item_creator_email) VALUES (?, ?, ?)");
    $createItem->bind_param("sss", $itemName, $itemDescription, $email);

    $findItems = $conn->prepare("SELECT item_id, item_name, item_description FROM Items WHERE item_creator_email=?");
    $findItems->bind_param("s", $email);

    $updateItem = $conn->prepare("UPDATE Items SET item_name=?, item_description=? WHERE item_id=?");
    $updateItem->bind_param("sss", $updatedItemName, $updatedItemDescription, $itemId);

    $deleteItem = $conn->prepare("DELETE FROM Items WHERE item_id=?");
    $deleteItem->bind_param("s", $itemId);
?>