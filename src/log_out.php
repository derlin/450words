<?php
session_start();
session_destroy();
header('Location: log_in.php');
exit;