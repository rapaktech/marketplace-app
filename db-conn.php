<?php
/* Modify $username, $password and $dbname variable to match your own */

$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "marketplace";
define('servername', 'localhost');
define('username', 'root');
define('pass', '');
define('dbname', 'marketplace');
$conn = new mysqli (servername, username, pass, dbname);


$createUser = $conn->prepare("INSERT INTO Users (user_firstname, user_lastname, user_email, user_password) 
    VALUES (?, ?, ?, ?)");
$createUser->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

$findUser = $conn->prepare("SELECT user_num, user_firstname, user_password FROM Users WHERE user_email=?");
$findUser->bind_param("s", $email);

function resetPass($hashed,$uId) {
    $conn = new mysqli (servername, username, pass, dbname);
    $res = false;
    try {
        $resetPassword = $conn->prepare("UPDATE Users SET user_password=? WHERE user_num=?");
        $resetPassword->bind_param("si", $hashed, $uId);
        $resetPassword->execute();
        $res = true;
    } catch (PDOException $e) {
        $res = $e->getMessage();
    }
    return $res;
}  


$createItem = $conn->prepare("INSERT INTO Items (item_name, item_description, item_price, item_creator_email) VALUES (?, ?, ?, ?)");
$createItem->bind_param("ssss", $itemName, $itemDescription, $itemPrice, $email);

$findItems = $conn->prepare("SELECT item_id, item_name, item_description, item_price FROM Items WHERE item_creator_email=?");
$findItems->bind_param("s", $email);

$updateItem = $conn->prepare("UPDATE Items SET item_name=?, item_description=?, item_price=? WHERE item_id=?");
$updateItem->bind_param("sssi", $updatedItemName, $updatedItemDescription, $updatedItemPrice, $itemId);

$deleteItem = $conn->prepare("DELETE FROM Items WHERE item_id=?");
$deleteItem->bind_param("i", $itemId);
?>
