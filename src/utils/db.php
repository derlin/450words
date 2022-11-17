<?php // db.php
// https://github.com/joshcam/PHP-MySQLi-Database-Class
function dbConnect($db = '')
{
    // in the form of "schema://<username>:<password>@<address>:<port>/<name>"
    $dburl = getenv('DATABASE_URL');

    $pattern = "{([^:]+)://([^:]+)(?::([^@]+))?@([^:]+):(\d+)/(.*)}";
    if (!preg_match($pattern, $dburl, $matches)) {
        echo "Could not extract database information from DATABASE_URL";
    }

    $user = $matches[2]; //getenv('MYSQL_USERNAME');
    $pass = $matches[3]; //getenv('MYSQL_PASSWORD');
    $host = $matches[4]; //getenv('MYSQL_URL');
    $port = $matches[5];
    $name = $matches[6];

    //ini_set('display_errors', '1');
    //error_reporting(E_ALL);

    $mysqli = new mysqli($host, $user, $pass, $name, intval($port));
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
