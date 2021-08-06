<?php
    require "db-conn.php";

    if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
        $verifyEmail = htmlspecialchars($_GET['email']);
        $verifyHash = htmlspecialchars($_GET['hash']);

        $update = verifyAndUpdate($verifyEmail, $verifyHash);

        if ($update === true) {
            echo '<div class="statusmsg">Your account has been activated, you can now login <a href=login.php>here</a>';
        } else {
            echo $update;
        } 
    } else {
        echo '<div class="statusmsg">Invalid Approach. Please use the link in your inbox to verify your account. Thanks.';
    }
    
?>