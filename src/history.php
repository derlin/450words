<?php
include_once 'utils/user_management.php';
include_once 'HistoryManager.php';

if (!is_user_logged_in()) {
    go_to_login_page();
}


$mydate = MyDate::from_string(isset($_GET['date']) ? $_GET['date'] : 'now');
$per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 15;
$page = isset($_GET['page']) ? $_GET['page'] : 0;

$historyManager = new HistoryManager($mydate, $page, $per_page);

global $curpage;
$curpage = "history";
include 'templates/header.php';  // the html: navigation
?>
<div class="container">
    <h2><?= $historyManager->get_display_description() ?></h2>
    <div class="entries-list">
        <?php $historyManager->print_entries() ?>
    </div>
</div>

<div class="pagination">
    <?php $historyManager->print_pagination() ?>
</div>

<?php

include 'templates/footer.php';