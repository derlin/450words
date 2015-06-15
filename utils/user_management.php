<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 15/06/15
 * Time: 21:52
 */

include_once 'db.php';
session_start();

function is_user_logged_in()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
}

function go_to_login_page($error_msg = '')
{
    if($error_msg != '') $_SESSION['error_message'] = $error_msg;
    header("Location: /log_in.php");
    exit(0);
}


function go_to_signin_page($error_msg = '')
{
    if($error_msg != '') $_SESSION['error_message'] = $error_msg;
    header("Location: /signup.php");
    exit(0);
}

function go_home()
{
    header("Location: /");
    exit(0);
}

// usage: list($ok, $error_msg) = login(...)
function log_in($uid, $pwd)
{
    // check required fields
    if (!isset($uid) || !isset($pwd)) {
        return array(false, 'Error - missing required field.');
    }

    // get the stored hash password from database
    $mysqli = dbConnect("words");
    $sql = $mysqli->prepare("select password from user where userid = ?");
    $sql->bind_param('s', $uid);


    if (!$sql->execute()) {
        // sql error
        $sql->close();
        $mysqli->close();
        return array(false, 'A database error occurred while checking your ' .
            'login details.<br />If this error persists, please ' .
            'contact sansy.dentity@gmail.com');
    }

    if (!$sql->num_rows != 1) {
        // username does not exist
        $sql->close();
        $mysqli->close();
        return array(false, 'Sorry, this username does not exist');
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
        return array(false, ' Access Denied. Your pseudo or password is incorrect
    .');
    }

    // set the session logged_in flag
    $_SESSION['uid'] = $uid;
    $_SESSION['logged_in'] = true;

    return array(true, '');
}


// usage: list($ok, $error_msg) = login(...)
function sign_in($userid, $pwd){
    // Process signup submission
    $mysqli = dbConnect('words');

    if ($userid == '' or $pwd == '') {
        return array(false, 'One or more required fields were left blank.' .
            'Please fill them in and try again.');
    }

    // Check for existing user with the new id
    $sql = $mysqli->prepare("SELECT userid FROM user WHERE userid = ?");
    $sql->bind_param('s', $userid);

    if (!$sql->execute()) {
        $sql->close();
        $mysqli->close();
        return array(false, 'A database error occurred in processing your ' .
            'submission.If this error persists, please ' .
            'contact you@example.com.');
    }

    $rows = $sql->num_rows;
    $sql->close();

    if ($rows > 0) {
        $mysqli->close();
        return array(false, 'A user already exists with your chosen userid.' .
            'Please try another.');
    }

    $hash = password_hash($pwd, PASSWORD_DEFAULT);

    $sql = $mysqli->prepare("INSERT INTO user values(?, ?)");
    $sql->bind_param('ss', $userid, $hash);
    $res = $sql->execute();
    $sql->close();
    $mysqli->close();

    if (!$res) {
        return array(false, 'A database error occurred in processing your ' .
            'submission.If this error persists, please ' .
            'contact you@example.com.' . mysql_error());
    }

    return array(true, '');
}