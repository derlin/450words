<?php
include_once 'utils/user_management.php';
include_once 'utils/utils.php';
include_once 'MyDate.php';

if (!is_user_logged_in()) {
    go_to_login_page();
}

header('Content-Type: text/markdown');
header("Content-Disposition: inline; filename=\"450words.md\"");

$document = "";

$mysqli = dbConnect("words");
$sql = $mysqli->prepare('select day, word from words where userid = ? order by day;');
$sql->bind_param('s', $_SESSION['uid']);
$sql->bind_result($day, $text);
$sql->execute();

while ($row = $sql->fetch()) {
    $document .= "\n\n\n## " .  $day . " - " . F::pretty(MyDate::from_string($day)) . "\n\n";
    $document .= entry_to_markdown($text);
}
$sql->close();
$mysqli->close();

echo $document;

?>