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

        .login {
            padding-top: 10px;
        }

        .signup {
            padding-top: 20px;
        }
    </style>
    <title>The Virtual Marketplace</title>
</head>
<body>
    <?php
        require "db-conn.php";
        $emailErr = $passwordErr = "";
        $email = $password = "";
        $verify = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["login-email"])) {
                $email = test_input($_POST["login-email"]);
                // check if e-mail address is well-formed
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                } else {
                    if (!checkEmail()) {
                        $email = false;
                        $_SESSION["email"] = false;
                        echo "This email hasn't been used before. Please check your email, or sign up below<br>";
                    } else {
                        $_SESSION["email"] = $email;
                    }
                }
            }


            if (isset($_POST["login-password"])) {
                $password = test_input($_POST["login-password"]);
                // check if password only contains letters and whitespace
                if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$password)) {
                    $passwordErr = "Password must have at least one number, 
                    one capital letter, no special characters and must be more than 8 characters";
                } else {
                    $verify = password_verify($password, $hash);
                    if (!$verify) {
                        echo "Password is incorrect. Please check and try again<br>";
                    }
                }
            }
            

            if (checkEmail() && $verify) {
                setcookie("jimmarketplaceuser[id]", "$id");
                setcookie("jimmarketplaceuser[firstname]", "$firstName");
                setcookie("jimmarketplaceuser[email]", "$email");
                echo "<script>
                        window.setTimeout(function() {
                            window.location.href = 'dashboard.php';
                        }, 100);
                </script>";
            }
        }
    
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        function checkEmail () {
            global $conn, $email, $id, $firstName, $password, $verifyHash, $hash, $findUser;
            $findUser->execute();
            $findUser->bind_result($foundUser, $userFirstName, $hashedPassword, $enabled, $verifyHash);
            while ($findUser->fetch()) {
                if ($foundUser && $enabled == 1) {
                    $id = $foundUser;
                    $firstName = $userFirstName;
                    $hash = $hashedPassword;
                    return true;
                    break;
                } else if ($foundUser && $enabled == 0) {
                    try {
                        sendEmail();
                    } catch (\Throwable $th) {
                        echo $th;
                    }
                    echo "Please check your email inbox for a verification inbox to verify your account";
                } else {
                    continue;
                }
            }
        }

        function sendEmail () {
            global $email, $password, $verifyHash;
            $to = $email;
            $subject = "Verify Your Email For Jim's Virtual Marketplace";
            $message = '
            
            Thanks for signing up!
            Your account has been created with the following credentials:


            Email Address: '.$email.'
            Password: '.$password.'


            Please click the link below to activate your account to start selling and buying on our marketplace:

            http://localhost:4000/code/marketplace-app/verify.php?email='.$email.'&hash='.$verifyHash.'
            
            ';

            $headers = 'From:noreply@jimezesinachi.com' . "\r\n";
            mail($to, $subject, $message, $headers);
        }
    ?>

    <h1>Welcome To The Virtual Marketplace</h1>
    
    <div class="login" name="login" id="login">
    <h3>Sign In Here</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="login-email">Email: </label>
            <input type="text" name="login-email" id="login-email" placeholder="Your Email">
            <span class="error">* <?php echo $emailErr;?></span><br><br>
            <label for="login-password">Password: </label>
            <input type="password" name="login-password" id="login-password" placeholder="Your Password">
            <span class="error">* <?php echo $passwordErr;?></span><br><br>
            <input type="submit" name="login-btn" id="login-btn" value="Sign In">
        </form>
    </div>

    <div class="signup" name="signup" id="signup">
    <h2>Not A User? Sign Up Here</h2>
    <form action="<?php echo htmlspecialchars("signup.php"); ?>" method="post">
        <input type="submit" name="signup-btn" id="signup-btn" value="Sign Up Here">
    </form>
    </div>
</body>
</html>