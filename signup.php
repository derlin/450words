<?php // signup.php

include("utils/db.php");

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
            <a class="navbar-brand" href="#">750 words</a>
        </div>
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
    else:
        // Process signup submission
        $mysqli = dbConnect('words');

        if ($_POST['newid'] == '' or $_POST['newpwd'] == '') {
            error('One or more required fields were left blank.' .
                'Please fill them in and try again.');
        }

        // Check for existing user with the new id
        $sql = $mysqli->prepare("SELECT userid FROM user WHERE userid = ?");
        $sql->bind_param('s', $_POST['newid']);

        if (!$sql->execute()) {
            $sql->close();
            $mysqli->close();
            error('A database error occurred in processing your ' .
                'submission.If this error persists, please ' .
                'contact you@example.com.');
        }

        $rows = $sql->num_rows;
        $sql->close();

        if ($rows > 0) {
            $mysqli->close();
            error('A user already exists with your chosen userid.' .
                'Please try another.');
        }

        $newpass = password_hash($_POST['newpwd'], PASSWORD_DEFAULT);

        $sql = $mysqli->prepare("INSERT INTO user values(?, ?)");
        $sql->bind_param('ss', $_POST['newid'], $newpass);
        $res = $sql->execute();
        $sql->close();
        $mysqli->close();

        if (!$res) {
            error('A database error occurred in processing your ' .
                'submission.If this error persists, please ' .
                'contact you@example.com.' . mysql_error());
        }

        ?>
        <p><strong>User registration successful!</strong></p>
    <?php
    endif;

    function error($string){
    ?>
    <p><?= $string ?></p>
</div>
</body>
</html>
<?php
exit;
}
?>
</div>
</body>
</html>
