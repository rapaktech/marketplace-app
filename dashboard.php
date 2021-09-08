<?php
    if (!isset($_COOKIE["jimmarketplaceuser"])) {
        die ("Please login to access this page");
    } else {
        require "db-conn.php";
        $userItems = [];
        $allItems = [];
        $itemId = false;

        foreach ($_COOKIE["jimmarketplaceuser"] as $key => $value) {
            if ($key == "id") {
                $id = $value;
            } if ($key == "firstname") {
                $firstName = $value;
            } if ($key == "lastname") {
                $lastName = $value;
            } if ($key == "email") {
                $email = $value;
            } if ($key == "phone") {
                $phone = $value;
            }
        }
        
        readAllItems();
        readUserItems();

        if (!empty($firstName)) {
            echo "<h1>Welcome {$firstName}</h1>";
        }
    }
?>



<?php
    // define variables and set to empty values
    $itemNameErr = $itemDescriptionErr = $itemPriceErr = "";
    $itemName = $itemDescription = $itemPrice = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout']) == true) {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time()-3600,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        setcookie("jimmarketplaceuser[id]", '', time()-3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        setcookie("jimmarketplaceuser[firstname]", '', time()-3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        setcookie("jimmarketplaceuser[lastname]", '', time()-3600, $params["path"], $params["domain"],$params["secure"], $params["httponly"]);
        setcookie("jimmarketplaceuser[phone]", '', time()-3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        session_destroy();
        header("location: index.html");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['profile']) == true) {
        header("location: profile.php");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) == true) {
        if (empty($_POST["item-name"])) {
            $itemNameErr = "Input field cannot be empty";
        } else if (empty($_POST["item-description"])) {
            $itemDescriptionErr = "Input field cannot be empty";
        } else if (empty($_POST["item-price"])) {
            $itemPriceErr = "Input field cannot be empty";
        } else {
            $itemName = test_input($_POST["item-name"]);
            $itemDescription = test_input($_POST["item-description"]);
            $itemPrice = test_input($_POST["item-price"]);
            $itemImage = uploadImage();
            if ($itemImage === false) {
                echo "Image Upload failed. Please try again";
            } else {
                $itemImageFileName = $itemImage;
                $result = $createItem->execute();
                if ($result)
                echo "<script>
                            window.setTimeout(function() {
                                window.location.href = 'dashboard.php';
                            }, 100);
                </script>";
                else echo "Item creation failed. Please try again";
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save']) == true) {
        if (empty($_POST["radio"])) {
            echo "Select one of the radio buttons to update";
        } else {
            if (empty($_POST["update-name"])) {
                echo "Update field cannot be empty. Please write something before you update";
            } else {
                $itemId = $_POST["radio"];
                $updatedItemDescription = test_input($_POST["update-description"]);
                $updatedItemName = test_input($_POST["update-name"]);
                $updatedItemPrice = test_input($_POST["update-price"]);
                $updateItem->execute();
                echo "<script>
                        window.setTimeout(function() {
                            window.location.href = 'dashboard.php';
                        }, 100);
                </script>";
            }
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-btn']) == true) {
        if (empty($_POST["radio"])) {
            echo "Select one of the radio buttons to delete";
        } else {
            $itemName = $_POST["radio"];
            $itemId = $userItems[$itemName][0];
            $deleteItem->execute();
            echo "<script>
                        window.setTimeout(function() {
                            window.location.href = 'dashboard.php';
                        }, 100);
            </script>";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function readUserItems () {
        global $conn, $id, $findItems, $userItems;
        $findItems->execute();
        $findItems->bind_result($itemId, $name, $description, $price, $image);
        while ($findItems->fetch()) {
            $userItems[$itemId] = [$itemId, $name, $description, $price, $image];
        }
    }

    function readAllItems () {
        global $allItems, $conn;
        $sql = "SELECT item_id, item_name, item_description, item_price, item_creator_phone, item_image FROM Items";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $allItems[$row["item_id"]] = [$row["item_id"], $row["item_name"], $row["item_description"], $row["item_price"], $row["item_creator_phone"], $row["item_image"]];
            }
            return true;
        } else {
            return false;
        }
    }

    function uploadImage () {
        require('guid-generator.php');
        require('config.php');

        $target_dir = "uploads/";

        $guid = strtolower(GuidGenerator::create());
        
        $target_file = $target_dir . $guid;

        $uploadOk = 1;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));



        // Check if image file is actual image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
                echo "File is not an image.<br>";
            }
        }


        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
            echo "Sorry, file already exists.<br>";
        }


        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 1024000) {
            $uploadOk = 0;
            echo "Image must be smaller than 1MB<br>";
        }


        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $cloudinary->uploadApi()->upload($target_file,
                ["public_id" => $guid]);

                return "https://res.cloudinary.com/jim-marketplace/image/upload/v1631099913/item_images/$guid.$imageFileType";
            } else {
                return false;
            }
        }
        return false;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .error {
            color: #FF0000;
        }

        h2 {
            color: green;
        }

        .items {
            padding-top: 50px;
        }
    </style>
</head>
<body>

    <div class="logout" name="logout">
        <form action="" method="post">
            <input type="submit" name="logout" value="Logout Session">
        </form>
    </div>

    <div class="profile" name="profile">
        <form action="" method="post">
            <input type="submit" name="profile" value="See Your Profile Information">
        </form>
    </div>

    <div class="add-item" name="add-item">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <h4>Add New Item To List:<h4> <input type="text" name="item-name" value="" placeholder="Item Name" required>
            <span class="error">* <?php echo $itemNameErr; ?></span><br><br>
            <input type="text" name="item-description" value="" placeholder="Describe Item" required>
            <span class="error">* <?php echo $itemDescriptionErr; ?></span><br><br>
            <input type="number" name="item-price" value="" placeholder="Price" required>
            <span class="error">* <?php echo $itemPriceErr; ?></span><br><br>
            <input type="file" name="fileToUpload" id="fileToUpload" placeholder="Select Item Image" required><br><br>
            <input type="submit" name="submit" value="Add Item"><br><br>
        </form>
    </div>

    <div class="items" name="user-items">
        <form id="radio-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h4>Your Items</h4>
            <?php 
                if (empty($userItems) == FALSE) {
                    foreach ($userItems as $key => $value) {
                        echo "<input type=\"radio\" name=\"radio\" id=\"{$value[1]}\" value=\"{$key}\" placeholder=\"{$value[2]}\" 
                        size=\"{$value[3]}\" onclick=\"handleClick(this);\"> <b>{$value[1]}</b>  
                        <span name=\"{$value[2]}\">   - {$value[2]}</span> <span name=\"{$value[3]}\">   - {$value[3]}</span>  <br><br>";
                        echo "<img src=\"{$value[4]}\" width=\"200px\" height=\"200px\" alt=\"{$value[1]}\">";
                    }
                } else {
                    echo "No items added yet";
                }
            ?>
                <input type="button" name="update-btn" id="update-btn" value="Update Item">
                <input type="submit" name="delete-btn" id="delete-btn" value="Delete Item">
        </form>
    </div>

    <div class="items" name="all-items">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h4>All Items</h4>
            <ul>
            <?php 
                if (readAllItems()) {
                    foreach ($allItems as $key => $value) {
                        echo "<li><h4 name=\"{$value[1]}\">{$value[1]}</h4>
                        <span name=\"{$value[2]}\">   - {$value[2]}</span>
                        <span name=\"{$value[3]}\">   - {$value[3]}</span>
                        <span name=\"{$value[4]}\">   - {$value[4]}</span>
                        <img src=\"{$value[5]}\" width=\"200px\" height=\"200px\" alt=\"{$value[1]}\"></li>";
                    }
                } else {
                    echo "No items added yet";
                }
            ?>
            </ul>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>