<?php
include_once 'utils/user_management.php';

if (!is_user_logged_in()) {
    go_to_login_page();
}

include_once 'MyDateManager.php';
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
                <a class="navbar-brand" href="#">450 words</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="/log_out.php">Log out</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>

    <?php

    $mydate = MyDate::from_string(isset($_GET['date']) ? $_GET['date'] : 'now');
    $datemgr = new MyDateManager($mydate);

    ?>
    <div class="container">

        <div class="text-center">
            <h1><?= $_SESSION['uid'] ?>: <?= F::month_year($mydate) ?></h1>


            <div>
                <?= $datemgr->print_month_overview() ?>
            </div>

        </div>

        <h2 class="green">
            <i id="this_day_ok" class="fa fa-square-o" style="font-size: 27px"></i>
            <?= F::pretty($mydate) ?>
        </h2>

        <div id="textarea_container">
            <?= $datemgr->print_text_area() ?>
        </div>

        <div id="saved" class="text-right" style="display:none">
            <span id="saved_words"></span> words saved at <span id="saved_time"></span>
            <span style="color:limegreen; font-size: 20px"><i class="fa fa-check"></i></span>
        </div>

        <div id="counts">
            <div style="font-weight: bold">
                <span class="wordCount">0</span> Words.
            </div>
            <div class="count_details">
                Total characters (including trails): <span id="totalChars">0</span><br/>
                Characters (excluding trails): <span id="charCount">0</span><br/>
                Characters (excluding all spaces): <span id="charCountNoSpace">0</span>
            </div>
        </div>

    </div>

    <div class="footer navbar-fixed-bottom">
        <div class="text-center">
            <span><i class="fa fa-copyright"></i> 450 words </span> |
            <span><a href="mailto:lucy.derlin@gmail.com">contact the author</a> |</span>
            <?= F::pretty($mydate) ?> |
            <!--<i class="fa fa-long-arrow-right"></i>-->
            <span class="wordCount">0</span> words.
        </div>
    </div>

    <!-- /.container -->
    <!-- script references -->
    <script type="text/javascript" src="/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/autosize.min.js"></script>
    <script type="text/javascript" src="/js/counter.js"></script>
    <script type="text/javascript" src="/js/autosave.js"></script>
    <script type="text/javascript">
        $( document ).ready( function(){
            $( "[data-toggle='tooltip']" ).tooltip();
        } );
    </script>
    </body>
    </html>
<?php