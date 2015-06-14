<?php include 'utils/accesscontrol.php'; ?>
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
        <link type="text/css" href="/css/bootstrap.min.css" rel='stylesheet'/>
        <link type="text/css" href="/css/styles.css" rel='stylesheet'/>
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
                <a class="navbar-brand" href="#">750 words</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="/sign_out.php">Log out</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>

    <?php
    $date = strtotime(isset($_GET['date']) ? $_GET['date'] : 'now');
    $days_in_month = date('t', $date);
    $first_day_of_month = strtotime(date('Y.m.1', $date));

    echo date("Y-m-d", $ts);
    ?>
    <div class="container">

        <div class="text-center">
            <h1>750 words</h1>

            <h2><?= date('F Y', $date) ?></h2>

            <div>

                <table id="months_progress" class="margin-auto">
                    <tr>
                        <td><a href="/protectedpage.php?date=<?= format_date(month($date, -1))?>">
                                &lt; &lt; </a></td>
                        <?php
                        for ($i = 1; $i <= $days_in_month; $i++) {
                            echo '<td><img src="/resources/no-points.png" height="30" title="' . $i . ' ' . date('F', $date) .
                                '"/></td>';
                        }
                        ?>
                        <td><a href="/protectedpage.php?date=<?= format_date(month($date, +1))?>">
                                &gt; &gt; </a></td>
                    </tr>
                </table>
            </div>

            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text
                and a
                mostly barebones HTML document.</p>
        </div>

    </div>
    <!-- /.container -->
    <!-- script references -->
    <script type="text/javascript" src="/js/jquery-1.11.3.min.js"/>
    <script type="text/javascript" src="/js/bootstrap.min.js"/>
    </body>
    </html>
<?php

function format_date($d)
{
    return date('Y-m-d', $d);
}

function month($d, $i){
    return mktime(0, 0, 0, date("n", $d) + $i, 1);
}

function add_date($d, $str)
{
    return strtotime(date('Y-m-d', $d) . $str);
}