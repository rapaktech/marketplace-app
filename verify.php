<?php
    require "db-conn.php";

    if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
        $email = mysql_escape_string($_GET['email']);
        $verifyHash = mysql_escape_string($_GET['hash']);

        $search = mysql_query("SELECT user_email, user_verify_hash FROM Users 
        WHERE email=$email AND hash=$verifyHash AND user_enabled='0'");

        $match = mysql_num_rows($search);

        if ($match > 0) {
            // We have a match, activate the account
            mysql_query("UPDATE Users SET user_enabled='1' WHERE email=$email AND hash=$verifyHash AND user_enabled='0'");
            echo '<div class="statusmsg">Your account has been activated, you can now login <a href=login.php>here</a>';
        } else {
            echo '<div class="statusmsg">You account has already been activated with this link before. 
            Please login <a href=login.php>here</a>';
        }
    } else {
        echo '<div class="statusmsg">Invalid Approach. Please use the link in your inbox to verify your account. Thanks.';
    }

?>