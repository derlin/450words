<?php // accesscontrol.php
include_once 'db.php';

session_start();

unset($_SESSION['error_message']);
$uid = isset($_POST['uid']) ? $_POST['uid'] : $_SESSION['uid'];
$pwd = $_POST['pwd'];

if(!isset($uid) || !isset($pwd)) {
    back_to_sign_in('Error - missing required field.');
}

$mysqli = dbConnect("words");
$sql = $mysqli->prepare("select password from user where userid = ?");
$sql->bind_param('s', $uid);


if (!$sql->execute()) {
    $sql->close();
    $mysqli->close();
    back_to_sign_in('A database error occurred while checking your '.
        'login details.<br />If this error persists, please '.
        'contact sansy.dentity@gmail.com');
}

if (!$sql->num_rows != 1) {
    $sql->close();
    $mysqli->close();
    back_to_sign_in('Sorry, this username does not exist');
}

$sql->bind_result($hash);
$sql->fetch();
$sql->close();
$mysqli->close();

// hash_equals: available for php >= 5.6
// Hashing the password with its hash as the salt returns the same hash
if (!password_verify($pwd, $hash) ) {
    back_to_sign_in($hash . ' - crypt -' . crypt($pwd, $hash) . ' Access Denied. Your pseudo or password is incorrect
    .');
}

unset($_SESSION['error_message']);
$_SESSION['uid'] = $uid;


function back_to_sign_in($error_msg){
    $_SESSION['error_message'] = $error_msg;
    header("Location: /log_in.php");
    exit(0);
}
?>