<?php // db.php

function dbConnect($db = '')
{
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'vieux-boucau';

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $db);
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
