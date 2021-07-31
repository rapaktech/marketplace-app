<?php
/* Modify $username, $password and $dbname variable to match your own */

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
    $conn = new mysqli (servername, username, pass, dbname);
}


$createUser = $conn->prepare("INSERT INTO Users (user_firstname, user_lastname, user_email, user_phone, user_verify_hash, user_password, user_enabled) 
    VALUES (?, ?, ?, ?, ?, ?)");
$createUser->bind_param("ssssss", $firstName, $lastName, $email, $phone, $verifyHash, $hashedPassword);

$findUser = $conn->prepare("SELECT user_num, user_firstname, user_password, user_enabled, user_verify_hash FROM Users WHERE user_email=?");
$findUser->bind_param("s", $email);

function resetPass($hashed, $uId) {
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


function verifyAndUpdate($e, $v) {
    $conn = new mysqli (servername, username, pass, dbname);
    $res = false;
    $num = 1;
    try {
        $verifyUser = $conn->prepare("SELECT user_num, user_enabled FROM Users WHERE user_email=? AND user_verify_hash=?");
        $verifyUser->bind_param("ss", $e, $v);

        $updateUser = $conn->prepare("UPDATE Users SET user_enabled=? WHERE user_email=?");
        $updateUser->bind_param("is", $num, $e);

        $verifyUser->execute();
        $verifyUser->bind_result($foundVerifyUser, $userEnabled);

        while ($verifyUser->fetch()) {
            if ($foundVerifyUser && $userEnabled === 0) {
                $updateUser->execute();
                break;
            } else if ($foundVerifyUser && $userEnabled === 1) {
                echo '<div class="statusmsg">You account has already been activated with this link before. 
                Please login <a href=login.php>here</a>';
                break;
            } else {
                continue;
            }
        }

        $res = true;
    } catch (PDOException $e) {
        $res = $e->getMessage();
    }
    return $res;
}


$createItem = $conn->prepare("INSERT INTO Items (item_name, item_description, item_price, item_creator_email, item_creator_phone) VALUES (?, ?, ?, ?, ?)");
$createItem->bind_param("sssss", $itemName, $itemDescription, $itemPrice, $email, $phone);

$findItems = $conn->prepare("SELECT item_id, item_name, item_description, item_price FROM Items WHERE item_creator_email=?");
$findItems->bind_param("s", $email);

$updateItem = $conn->prepare("UPDATE Items SET item_name=?, item_description=?, item_price=? WHERE item_id=?");
$updateItem->bind_param("sssi", $updatedItemName, $updatedItemDescription, $updatedItemPrice, $itemId);

$deleteItem = $conn->prepare("DELETE FROM Items WHERE item_id=?");
$deleteItem->bind_param("i", $itemId);
?>
