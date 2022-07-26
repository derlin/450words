<?php
    global $curpage;
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>450 words</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link type="text/css" href="/css/bootstrap.min.css" rel='stylesheet'/>
    <link type="text/css" href="/css/styles.css" rel='stylesheet'/>
    <link type="text/css" href="/css/font-awesome-4.3.0/css/font-awesome.min.css" rel='stylesheet'/>
    <link href='http://fonts.googleapis.com/css?family=Bree+Serif' rel='stylesheet' type='text/css'>
</head>

<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">450 words</a>
        </div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?php if($curpage == 'index') echo 'class="active"'?>><a href="/">Home</a></li>
                <li <?php if($curpage == 'history') echo 'class="active"'?>><a href="history.php">History</a></li>
                <li><a href="export.php" target="_new">Export</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if(isset($_SESSION['uid'])) {
                    ?>
                    <li><p class="navbar-text">Logged in as <?= $_SESSION['uid'] ?></p></li>
                    <li><a href="/log_out.php">Log out</a></li>
                    <?php
                }
                ?>

            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</div>

