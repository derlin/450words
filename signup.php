<?php // signup.php

//////////////////////////////////////////////////

//echo 'not available yet';
//exit;

//////////////////////////////////////////////////

include("utils/db.php");
include("utils/user_management.php");

?>
<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>780 words</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link type="text/css" href="css/bootstrap.min.css" rel='stylesheet'/>
    <link type="text/css" href="css/styles.css" rel='stylesheet'/>
</head>
<body>


<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">750 words</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="/log_in.php">Log in</a></li>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

<div class="container">
    <h3>New User Registration Form</h3>

    <?php
    if (!isset($_POST['submitok'])):
        // Display the user signup form
        ?>
        <form class="form" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
            <div class="form-group">
                <label for="name">Pseudo</label>
                <input type="text" class="form-control" id="newid" name="newid" placeholder="Pseudo">
            </div>
            <div class="form-group">
                <label for="pass">Password</label>
                <input type="password" class="form-control" id="newpwd" name="newpwd" placeholder="Password">
            </div>
            <div class="form-group">
                <button type="submit" name="submitok" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <?php
        if (isset($_SESSION['error_message'])) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <strong>Error!</strong> <?= $_SESSION['error_message'] ?>
            </div>
        <?php
        }
        ?>

    <?php
    else:
        // Process signup submission
        list($res, $error_msg) = sign_in($_POST['newid'], $_POST['newpwd']);

        if (!$res) {
            back_to_sign_in($error_msg);
        }
        ?>
        <p><strong>User registration successful!</strong></p>
        <p>Now, you can <a href="/log_in.php">log in</a>.</p>
    <?php
    endif;
    ?>
</div>
</body>
</html>