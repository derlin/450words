<?php
/**
 * File:        accesscontrol.php
 *
 * Abstract:    This file checks if the user is logged in.
 *              If not, two cases:
 *               - get: it will just redirect to log_in.php
 *               - post: it will check for the username and
 *                  password. If the login succeeds, it does
 *                  nothing and pass the control back to the
 *                  includer.
 *
 * Author:      Lucy Linder <lucy.derlin@gmail.com>
 * Date:        June 2015
 *
 */
include_once 'db.php';

session_start();

if (!isset($_SESSION['logged_in'])):
    // user did not log in

    unset($_SESSION['error_message']);
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];

    // check required fields
    if (!isset($uid) || !isset($pwd)) {
        back_to_sign_in('Error - missing required field.');
    }

    // get the stored hash password from database
    $mysqli = dbConnect("words");
    $sql = $mysqli->prepare("select password from user where userid = ?");
    $sql->bind_param('s', $uid);


    if (!$sql->execute()) {
        // sql error
        $sql->close();
        $mysqli->close();
        back_to_sign_in('A database error occurred while checking your ' .
            'login details.<br />If this error persists, please ' .
            'contact sansy.dentity@gmail.com');
    }

    if (!$sql->num_rows != 1) {
        // username does not exist
        $sql->close();
        $mysqli->close();
        back_to_sign_in('Sorry, this username does not exist');
    }

    // retrieve the hash
    $sql->bind_result($hash);
    $sql->fetch();
    $sql->close();
    $mysqli->close();

    // hash_equals: available for php >= 5.6
    // Hashing the password with its hash as the salt returns the same hash
    if (!password_verify($pwd, $hash)) {
        // wrong password
        back_to_sign_in($hash . ' - crypt -' . crypt($pwd, $hash) . ' Access Denied. Your pseudo or password is incorrect
    .');
    }

    // remove old error message and set the session logged_in flag
    unset($_SESSION['error_message']);
    $_SESSION['uid'] = $uid;
    $_SESSION['logged_in'] = true;

endif;

// return to the loggin page, with an optional error message
function back_to_sign_in($error_msg)
{
    $_SESSION['error_message'] = $error_msg;
    header("Location: /log_in.php");
    exit(0);
}

?>