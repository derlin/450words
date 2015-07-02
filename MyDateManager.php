<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 14/06/15
 * Time: 16:25
 */
include 'MyDate.php';

class MyDateManager
{

    private $date, $max_streak, $streak;
    private $existing_words = array();

    public function __construct($mydate)
    {
        $this->date = $mydate;
        $this->streak = 0;
        $this->max_streak = 0;

        $mysqli = dbConnect("words");
        $sql = $mysqli->prepare('select day, word from words where userid = ? and day between ? and ? order by day;');
        $sql->bind_param('sss', $_SESSION['uid'], //
            F::link($this->date->get_date_for(1)), //
            F::link($this->date->get_date_for(31)));

        $sql->bind_result($day, $text);
        $sql->execute();

        while ($row = $sql->fetch()) {
            $this->existing_words[$day] = $text;
        }
        $sql->close();
        $mysqli->close();
    }

    public function print_month_overview()
    {
        $c = $this->date->get_nbr_of_days();
        ?>
        <div id="months_progress" class="margin-auto">
            <?php
            echo $this->print_month_link(-1, 'arrow-left');
            for ($i = 1; $i <= $c; $i++) {
                echo $this->print_badge($i);
            }
            echo $this->print_month_link(1, 'arrow-right');
            // also update the max streak since it is the last day processed
            if($this->streak > $this->max_streak) $this->max_streak = $this->streak;
            ?>

        </div>
        <div id="streak" class="text-right">Max streak <?= $this->max_streak ?>.</div>
    <?php
    }

    public function print_month_link($i, $fa_type)
    {
        $link = F::link($this->date->month_offset($i));
        ?>
        <a href="/?date=<?= $link ?>"><i class="fa fa-<?= $fa_type ?>"></i></a>
    <?php
    }

    public function print_text_area()
    {
        if ($this->date->is_now()) {
            ?>
            <div class="text-right">
                <button class="btn btn-default" onclick="save()">save</button>
            </div>
            <form>
                <input type="text" id="day" name="day" hidden="hidden" value="<?= (string)$this->date ?>"/>
                <textarea id="entry_body" name="entry_body"
                    ><?= $this->get_cur_text_entry(false) ?></textarea>
            </form>
        <?php
        } else {
            ?>
            <div id="entry_body">
                <?= $this->get_cur_text_entry() ?>
            </div>
        <?php
        }
    }


    public function get_cur_text_entry($as_html = true)
    {
        $text = "";
        $key = F::link($this->date);
        if (array_key_exists($key, $this->existing_words)) {
            $text = $this->existing_words[$key];
            $text = str_replace('\n', 'NNEWLINEE', $text);
            $text = stripslashes($text);
            $text = str_replace('NNEWLINEE', $as_html ? '<br />' : '&#10;', $text);
        }
        return $text;
    }

    private function print_badge($i)
    {
        $d = $this->date->get_date_for($i);
        $d_str = (string)$d;
        $title = $d_str . ' : ';
        $href = "javascript:void(0)";
        $badge_color = "";

        if (array_key_exists($d_str, $this->existing_words)) {
            //$count = str_word_count($this->sanitize(strip_tags($this->existing_words[$d_str])));
            $count = $this->count_words($this->existing_words[$d_str]);
            $badge_color .= $count >= 450 ? '#4DB559' : 'chocolate';
            $title .= $count . ' words!';
            $href = "/?date=" . $d_str;

        } elseif ($d->is_future()) { // future entry
            $badge_color .= 'lightgrey';
            $title .= 'not available yet';

        } else {
            $badge_color .= 'grey';
            $title .= 'you missed this day!';
        }

        if ($d->is_now()) {
            if (!$this->date->is_now()) {
                $href = "/?date=" . $d_str;
            }
            $title = "TODAY " . $count . " words.";

        }


        ?>
        <a href="<?= $href ?>">
            <span class="badge" data-toggle="tooltip" data-placement="bottom" title="<?= $title ?>"
                  style="background-color:<?= $badge_color ?>"><?= $i ?></span>
        </a>
    <?php

    }

    private function count_words($string)
    {
        $st = str_replace('\n', ' ', strip_tags($string));
        $count = count(preg_split('/\s+/', trim($st)));

        if ($count >= 450) {
            $this->streak += 1;
        } else {
            if ($this->streak > $this->max_streak)
                $this->max_streak = $this->streak;
            $this->streak = 0;
        }

        return $count;
    }
}