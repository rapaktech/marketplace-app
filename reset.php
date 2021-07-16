<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .error {
            color: #FF0000;
        }
    </style>
    <title>The Virtual Marketplace</title>
</head>
<body>
    <?php
    require "db-conn.php";
    $emailErr = $passwordErr = "";
    $email = $reset= $password = $verifiedPassword = $userId= "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset-btn"])) {
        if (!empty($_POST["reset-email"])) {
            $email = test_input($_POST["reset-email"]);
                // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            } else {
                if (checkEmail() == FALSE) {
                    $email = false;
                    $_SESSION["email"] = false;
                    echo "This email hasn't been used before. Please check your email, or sign up below<br>";
                } else if (checkEmail() == TRUE) {
                    $_SESSION["email"] = $email;
                }
            }
            if (!empty($_POST["reset-password"])) {
                $password = test_input($_POST["reset-password"]);
                    // check if password only contains letters and whitespace
                if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$password)) {
                    $passwordErr = "Password must have at least one number, 
                    one capital letter, no special characters and must be more than 8 characters";
                } else {
                    $verifiedPassword = $password;
                    $hashedPassword = password_hash($verifiedPassword, PASSWORD_DEFAULT);
                    $reset_result = resetPass($hashedPassword,$userId);
                    if ($reset_result === true) {
                        echo "Password has been reset";
                    }else{
                        echo $reset_result;
                    }
                }
            }
        }
    }
    
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function checkEmail () {
        global $email, $userId, $findUser;
        $findUser->execute();
        $findUser->bind_result($foundUser, $userFirstName, $hash);
        while ($findUser->fetch()) {
            if ($foundUser) {
                $userId = $foundUser;
                return true;
                break;
            } else {
                return false;
                break;
            }
        }
    }
    ?>

    <h1>Reset Your Password</h1>
    
    <div class="reset" name="reset" id="reset">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="reset-email">Email: </label>
            <input type="text" name="reset-email" id="reset-email" placeholder="Your Email">
            <span class="error">* <?php echo $emailErr;?></span><br><br>
            <label for="reset-password">New Password: </label>
            <input type="password" name="reset-password" id="reset-password" placeholder="Your New Password">
            <span class="error">* <?php echo $passwordErr;?></span><br><br>
            <input type="submit" name="reset-btn" id="reset-btn" value="Reset">
        </form>
    </div>

    <div class="signup" name="signup" id="signup">
        <h2>Or Sign Up Here</h2>
        <form action="<?php echo htmlspecialchars("index.php"); ?>" method="post">
            <input type="submit" name="signup-btn" id="signup-btn" value="Sign Up Here">
        </form>
    </div>
</body>
</html>
