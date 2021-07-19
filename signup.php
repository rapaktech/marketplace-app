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

        .signup {
            padding-top: 10px;
        }

        .login {
            padding-top: 20px;
        }
    </style>
    <title>The Virtual Marketplace</title>
</head>
<body>
    <?php
        require "db-conn.php";
        $firstErr = $lastErr = $emailErr = $passwordErr = $verifyErr =  "";
        $firstName = $lastName = $email = $password = $verify = $verifiedPassword = $hashedPassword = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (!empty($_POST["signup-firstname"])) {
                    $firstName = test_input($_POST["signup-firstname"]);
                    // check if name only contains letters and whitespace
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$firstName)) {
                        $firstErr = "Only letters and white space allowed";
                    } else {
                        $_SESSION["firstname"] = $firstName;
                    }
                }

                if (!empty($_POST["signup-lastname"])) {
                    $lastName = test_input($_POST["signup-lastname"]);
                    // check if last name only contains letters and whitespace
                    if (!preg_match("/^[a-zA-Z-' ]*$/",$lastName)) {
                        $lastErr = "Only letters and white space allowed";
                    } else {
                        $_SESSION["lastname"] = $lastName;
                    }
                }

                if (!empty($_POST["signup-email"])) {
                    $email = test_input($_POST["signup-email"]);
                    // check if e-mail address is well-formed
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    } else {
                        if (checkEmail()) {
                            $email = false;
                            $_SESSION["email"] = false;
                            echo "This email has been used before. Please use another email, or sign in, or reset your password if you've forgotten it";
                        } else {
                            $_SESSION["email"] = $email;
                        }
                    }
                }
            
                if (!empty($_POST["signup-password"]) && !empty($_POST["verify-password"])) {
                    $password = test_input($_POST["signup-password"]);
                    $verify = test_input($_POST["verify-password"]);

                    // check if password only contains letters and whitespace
                    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$password)) {
                        $passwordErr = "Password must have at least one number, no special characters and must be more than 8 characters";
                    } else {
                        if ($password !== $verify) {
                            $verifyErr = "Both password fields must be the same";
                        } else {
                            $verifiedPassword = $password;
                        }
                    }
                }

                if ($_SESSION["firstname"] && $_SESSION["lastname"] && $_SESSION["email"] && $verifiedPassword) {
                    /* Secure password hash. */
                    $hashedPassword = password_hash($verifiedPassword, PASSWORD_DEFAULT);
                    $createUser->execute();
                    session_unset();
                    session_destroy();
                    echo "You've successfully registered your data. You can now login here: 
                    <form action=\"login.php\" method=\"post\">
                    <input type=\"submit\" name=\"signin-btn\" id=\"signin-btn\" value=\"Sign In Here\"></form>";
                    echo "<br>";
                }
        }
    
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function checkEmail () {
            global $conn, $email, $findUser;
            $findUser->execute();
            $findUser->bind_result($foundUser, $userFirstName, $hashedPassword);
            while ($findUser->fetch()) {
                if ($foundUser) {
                    return true;
                    break;
                } else {
                    continue;
                }
            }
        }
    ?>


    <h1>Welcome To The Virtual Marketplace</h1>
    
    <div class="signup" name="signup" id="signup">
    <h2>Join The Virtual Marketplace</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="signup-firstname">First Name: </label>
        <input type="text" name="signup-firstname" id="signup-firstname" placeholder="Your First Name" value="<?php if(!empty($firstName)) { echo $firstName; } ?>" required>
        <span class="error">* <?php echo $firstErr;?></span><br><br>
        <label for="signup-lastname">Last Name: </label>
        <input type="text" name="signup-lastname" id="signup-lastname" placeholder="Your Last Name" value="<?php if(!empty($lastName)) { echo $lastName; } ?>" required>
        <span class="error">* <?php echo $lastErr;?></span><br><br>
        <label for="signup-email">Email: </label>
        <input type="text" name="signup-email" id="signup-email" placeholder="Your Email" value="<?php if(!empty($email)) { echo $email; } ?>" required>
        <span class="error">* <?php echo $emailErr;?></span><br><br>
        <label for="signup-password">Password: </label>
        <input type="password" name="signup-password" id="signup-password" placeholder="Your Preferred Password" required>
        <span class="error">* <?php echo $passwordErr;?></span><br><br>
        <label for="verify-password">Verify Password: </label>
        <input type="password" name="verify-password" id="verify-password" placeholder="Write Password Again" required>
        <span class="error">* <?php echo $verifyErr;?></span><br><br>

        <input type="submit" name="signup-btn" id="signup-btn" value="Sign Up">
    </form>
    </div>

    
    <div class="login" name="login" id="login">
    <h3>Already A User? Sign In Here!</h3>
        <form action="<?php echo htmlspecialchars("login.php"); ?>" method="post">
        <input type="submit" name="signin-btn" id="signin-btn" value="Sign In Here">
        </form>
    </div>

    <div class="reset" name="reset" id="reset">
    <h3>Forgot Your Password? Reset Your Passsword Here</h3>
        <form action="<?php echo htmlspecialchars("reset.php"); ?>" method="post">
        <input type="submit" name="reset-btn" id="reset-btn" value="Reset Password">
        </form>
    </div>
</body>
</html>