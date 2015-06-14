<?php // accesscontrol.php
include_once 'common.php';
include_once 'db.php';

session_start();

$uid = isset($_POST['uid']) ? $_POST['uid'] : $_SESSION['uid'];
$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : $_SESSION['pwd'];

if(!isset($uid)) {
    $_SESSION['error_message'] = 'No uid.';
    back_to_sign_in();
}

$_SESSION['uid'] = $uid;
$_SESSION['pwd'] = $pwd;

dbConnect("words");
$sql = sprintf("SELECT * FROM user
    WHERE userid='%s' AND password='%s'",
    mysql_real_escape_string(uid),
    mysql_real_escape_string($pwd));

$result = mysql_query($sql);



if (!$result) {
    $_SESSION['error_message'] = 'A database error occurred while checking your '.
        'login details.\\nIf this error persists, please '.
        'contact sansy.dentity@gmail.com';
    back_to_sign_in();
}

if (mysql_num_rows($result) == 0) {
    unset($_SESSION['uid']);
    unset($_SESSION['pwd']);
    $_SESSION['error_message'] = 'Access Denied. Your pseudo or password is incorrect.';
    back_to_sign_in();
}

unset($_SESSION['error_message']);
$username = $_SESSION['uid']; // mysql_result($result,0,'fullname');


function back_to_sign_in(){
    header("Location: /sign_in.php");
    exit(0);
}
?>
