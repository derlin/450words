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
    return $mysqli;
}

?>
