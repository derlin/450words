<?php

include_once 'utils/user_management.php';
session_start();

if (!isset($_POST['submitok'])):
// display the login form
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
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">450 words</a>
            </div>
        </div>
    </div>

    <div class="container">


        <div class="text-center">
            <h1>You must login to continue...</h1>
        </div>

        <?php
        if (isset($_SESSION['error_message'])) {
            ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <strong>Error!</strong> <?= $_SESSION['error_message'] ?>
            </div>
        <?php
            unset( $_SESSION['error_message']);
        }
        ?>

        <div style="max-width: 400px; margin:60px auto">
            <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                <div class="form-group">
                    <label for="name">Pseudo</label>
                    <input type="text" class="form-control" id="name" name="uid" placeholder="Pseudo">
                </div>
                <div class="form-group">
                    <label for="pass">Password</label>
                    <input type="password" class="form-control" id="pass" name="pwd" placeholder="Password">
                </div>

                <div class="text-right">
                    <button type="submit" name="submitok" class="btn btn-primary">Submit</button>
                </div>
            </form>

        </div>

        <p class="text-center">No account? Create one <a href="signup.php">here.</a></p>
    </div>
    <!-- /.container -->
    <!-- script references -->
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"/>
    <script type="text/javascript" src="js/bootstrap.min.js"/>
    </body>
    </html>

<?php
else:
    // process the post
    unset($_SESSION['error_message']);
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];

    list($res, $error_msg) = log_in($uid, $pwd);
    if (!$res) {
        go_to_login_page($error_msg);
    } else {
        go_home();
    }

endif;