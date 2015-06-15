<?php
include_once('utils/db.php');
session_start();
global $username;

$uid = $_SESSION['uid'];
$day = $_POST['day'];
$word = $_POST['word'];

if (empty($word) or empty($uid)) {
    echo 'empty';
}
$mysqli = dbConnect("words");
$word = $mysqli->real_escape_string($_POST['word']);
$sql = $mysqli->prepare("select day from words where userid = ? and day = ?");

$sql->bind_param('ss', $uid, $day);
$sql->execute();
$sql->store_result();

$result = $sql->num_rows;

$sql->close();

if ($result == 0) { // no records, insert
    $sql = $mysqli->prepare("insert into words values(?, ?, ?)") or die(mysqli_error($db));;
    $ret = $sql->bind_param('sss', $uid, $day, $word) or die(mysqli_error($db));;
    $ret = $sql->execute() or die(mysqli_error($db));;
    echo 'insert ' . $ret;

} else { // already a record, update
    $sql = $mysqli->prepare("update words set word = ? where userid = ? and day = ?") or die(mysqli_error($db));;
    $ret = $sql->bind_param('sss', $word, $uid, $_POST['day']) or die(mysqli_error($db));;
    $ret = $sql->execute() or die(mysqli_error($db));;
    echo 'update ' . $ret;

}
$sql->close();
$mysqli->close();
