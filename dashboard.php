<?php
    session_start();
    if (isset($_SESSION["id"]) && isset($_SESSION["firstname"]) && isset($_SESSION["email"]) == FALSE) {
        die ("Please login to access this page");
    }
    require "db-conn.php";
    $userItems = [];
    $allItems = [];
    $itemId = false;
    $id = $_SESSION["id"];
    $name = $_SESSION["firstname"];
    $email = $_SESSION["email"];
    readAllItems();
    readUserItems();
    echo "<h1>Welcome {$name}</h1>";
?>



<?php
    // define variables and set to empty values
    $itemNameErr = $itemDescriptionErr = "";
    $itemName = $itemDescription ="";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout']) == true) {
        session_unset();
        session_destroy();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) == true) {
        if (empty($_POST["item-name"])) {
            $itemNameErr = "Input field cannot be empty";
        } else if (empty($_POST["item-description"])) {
            $itemDescriptionErr = "Input field cannot be empty";
        } else {
            $itemName = test_input($_POST["item-name"]);
            $itemDescription = test_input($_POST["item-description"]);
            $createItem->execute();
            echo "<h2>Item Added Successfully. Please refresh page to see your changes</h2>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save']) == true) {
        if (empty($_POST["radio"])) {
            echo "Select one of the radio buttons to update";
        } else {
            if (empty($_POST["update"])) {
                echo "Update field cannot be empty. Please write something before you update";
            } else {
                $itemName = $_POST["radio"];
                $itemId = $userItems[$itemName][0];
                $updatedItemDescription = test_input($_POST["update-description"]);
                $updatedItemName = test_input($_POST["update"]);
                $updateItem->execute();
                echo "<h2>Update Successful. Please refresh page to see your changes</h2>";
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
            echo "<h2>Delete Successful. Please refresh page to see your changes</h2>";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function readUserItems () {
        global $conn, $email, $findItems, $userItems;
        $findItems->execute();
        $findItems->bind_result($id, $name, $description);
        while ($findItems->fetch()) {
            $userItems[$name] = [$id, $name, $description];
        }
    }

    function readAllItems () {
        global $allItems, $conn;
        $sql = "SELECT item_id, item_name, item_description FROM Items";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $allItems[$row["item_id"]] = [$row["item_id"], $row["item_name"], $row["item_description"]];
            }
            return true;
        } else {
            return false;
        }
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
        <form action="<?php echo htmlspecialchars("index.php"); ?>" method="post">
            <input type="submit" name="logout" value="Logout Session">
        </form>
    </div>

    <div class="add-item" name="add-item">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h4>Add New Item To List:<h4> <input type="text" name="item-name" value="" placeholder="Item Name" required>
            <span class="error">* <?php echo $itemNameErr; ?></span><br><br>
            <input type="text" name="item-description" value="" placeholder="Describe Item" required>
            <span class="error">* <?php echo $itemDescriptionErr; ?></span><br><br>
            <input type="submit" name="submit" value="Add Item">
        </form>
    </div>

    <div class="items" name="user-items">
        <form id="radio-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h4>Your Items</h4>
            <?php 
                if (empty($userItems) == FALSE) {
                    foreach ($userItems as $key => $value) {
                        echo "<input type=\"radio\" name=\"radio\" id=\"{$key}\" value=\"{$key}\"> <b>{$key}</b> <span name=\"{$value[2]}\">   - {$value[2]}</span>  <br><br>";
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
                        <span name=\"{$value[2]}\">   - {$value[2]}</span></li>";
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