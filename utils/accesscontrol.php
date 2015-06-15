<?php // accesscontrol.php
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

echo crypt($pwd);
$mysqli = dbConnect("words");
$sql = $mysqli->prepare("select count(*) as c from user where userid = ? and password = ?");
$sql->bind_param('ss', $uid, $pwd);


if (!$sql->execute()) {
    $sql->close();
    $_SESSION['error_message'] = 'A database error occurred while checking your '.
        'login details.<br />If this error persists, please '.
        'contact sansy.dentity@gmail.com';
    back_to_sign_in();
}

$sql->store_result();
$result =$sql->num_rows;
$sql->close();


if ($result != 1) {
    unset($_SESSION['uid']);
    unset($_SESSION['pwd']);
    $_SESSION['error_message'] = 'Access Denied. Your pseudo or password is incorrect.';
    back_to_sign_in();
}


unset($_SESSION['error_message']);



function back_to_sign_in(){
    header("Location: /log_in.php");
    exit(0);
}
?>
