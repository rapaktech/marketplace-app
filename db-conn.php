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


$createUser = $conn->prepare("INSERT INTO Users (user_firstname, user_lastname, user_email, user_phone, user_verify_hash, user_password) 
    VALUES (?, ?, ?, ?, ?, ?)");
$createUser->bind_param("ssssss", $firstName, $lastName, $email, $phone, $verifyHash, $hashedPassword);

$findUser = $conn->prepare("SELECT user_num, user_phone, user_firstname, user_lastname, user_password, user_enabled, user_verify_hash FROM Users WHERE user_email=?");
$findUser->bind_param("s", $email);

$createItem = $conn->prepare("INSERT INTO Items (item_name, item_description, item_price, item_creator_id, item_creator_phone, item_image) VALUES (?, ?, ?, ?, ?, ?)");
$createItem->bind_param("ssssss", $itemName, $itemDescription, $itemPrice, $id, $phone, $itemImageFileName);

$findItems = $conn->prepare("SELECT item_id, item_name, item_description, item_price, item_image FROM Items WHERE item_creator_id=?");
$findItems->bind_param("s", $id);

$updateItem = $conn->prepare("UPDATE Items SET item_name=?, item_description=?, item_price=? WHERE item_id=?");
$updateItem->bind_param("sssi", $updatedItemName, $updatedItemDescription, $updatedItemPrice, $itemId);

$deleteItem = $conn->prepare("DELETE FROM Items WHERE item_id=?");
$deleteItem->bind_param("i", $itemId);


function resetPass($hashed, $uId) {
    global $conn;
    $res = false;
    try {
        $resetPassword = $conn->prepare("UPDATE Users SET user_password=? WHERE user_num=?");
        $resetPassword->bind_param("si", $hashed, $uId);
        $resetPassword->execute();
        $res = true;
    } catch (\Throwable $e) {
        $res = $e->getMessage();
    }
    return $res;
}


function verifyAndUpdate($e, $v) {
    global $conn;
    $res = false;
    try {
        $verifyUser = $conn->prepare("SELECT user_num, user_enabled FROM Users WHERE user_email=? AND user_verify_hash=?");
        $verifyUser->bind_param("ss", $e, $v);

        $verifyUser->execute();
        $verifyUser->bind_result($foundVerifyUser, $userEnabled);

        while ($verifyUser->fetch()) {
            if ($foundVerifyUser && $userEnabled == '0') {
                try {
                    $conn = new mysqli (servername, username, pass, dbname);

                    if ($conn->connect_error) {
                        $conn = new mysqli (servername, username, pass, dbname);
                    }
                    
                    $updateUser = $conn->prepare("UPDATE Users SET user_enabled='1' WHERE user_num=?");
                    $updateUser->bind_param("i", $foundVerifyUser);
                    $updateUser->execute();
                } catch (\Throwable $th) {
                    return $res = $th->getMessage();
                }
                
                $res = true;
                return $res;
                break;
            } else if ($foundVerifyUser && $userEnabled == '1') {
                echo '<div class="statusmsg">You account has already been activated with this link before. 
                Please login <a href=login.php>here</a>';
                break;
            } else {
                continue;
            }
        }
    } catch (\Throwable $e) {
        $res = $e->getMessage();
    }
    return $res;
}


function updateLoggedInUser($id, $firstName, $lastName, $phone) {
    global $conn;
    $res = false;
    try {
        $userId = intval($id);
        
        $conn = new mysqli (servername, username, pass, dbname);

        if ($conn->connect_error) {
            $conn = new mysqli (servername, username, pass, dbname);
        }
        
        $updateUser = $conn->prepare("UPDATE Users SET user_phone=?, user_firstname=?, user_lastname=?, WHERE user_num=?");
        $updateUser->bind_param("sssi", $phone, $firstName, $lastName, $userId);
        $updateUser->execute();


        $conn = new mysqli (servername, username, pass, dbname);

        if ($conn->connect_error) {
            $conn = new mysqli (servername, username, pass, dbname);
        }

        $updateItem = $conn->prepare("UPDATE Items SET item_creator_phone=? WHERE item_creator_id=?");
        $updateItem->bind_param("ss", $phone, $id);
        $updateItem->execute();
        
        $res = true;
    } catch (\Throwable $e) {
        $res = $e->getMessage();
    }
    return $res;
}

?>