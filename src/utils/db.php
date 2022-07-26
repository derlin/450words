<?php // db.php
// https://github.com/joshcam/PHP-MySQLi-Database-Class
function dbConnect($db = '')
{
    $dbhost = getenv('MYSQL_URL');
    $dbuser = getenv('MYSQL_USERNAME');
    $dbpass = getenv('MYSQL_PASSWORD');

    //ini_set('display_errors', '1');
    //error_reporting(E_ALL);

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $db);
    $mysqli->autocommit(true);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    /* change character set to utf8 */
    if (!$mysqli->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
    return $mysqli;
}
?>
