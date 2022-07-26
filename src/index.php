<?php
include_once 'utils/user_management.php';

if (!is_user_logged_in()) {
    go_to_login_page();
}

include_once 'EntryManager.php';

$mydate = MyDate::from_string(isset($_GET['date']) ? $_GET['date'] : 'now');
$datemgr = new EntryManager($mydate);

global $curpage;
$curpage = "index";
include 'templates/header.php';  // the html: navigation
?>
<div class="container">

    <div class="text-center">
        <h1>
            <div class="flex">
                <div class="icon"><?= $datemgr->print_month_link(-1, 'arrow-left'); ?></div>
                <div class="title"><?= F::month_year($mydate) ?></div>
                <div class="icon"><?= $datemgr->print_month_link(1, 'arrow-right'); ?></div>
            </div>
        </h1>


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

 <!-- /.container -->
<?php include 'templates/footer.php' ?>

