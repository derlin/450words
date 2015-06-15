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
        $sql = $mysqli->prepare('select day, word from words where userid = ? and day between ? and ?;');
        $sql->bind_param('sss', $_SESSION['uid'], //
            F::link($this->date->get_date_for(1)), //
            F::link($this->date->get_date_for(31)));

        $sql->bind_result($day, $text);
        $sql->execute();

        while ($row = $sql->fetch()) {
            $this->existing_words[$day] = $text;
        }
        $sql->close();
    }

    public function print_month_table()
    {
        $c = $this->date->get_nbr_of_days();
        ?>
        <table id="months_progress" class="margin-auto">
            <tr>
                <?php
                echo $this->print_month_row(-1, ' &lt; &lt;');
                for ($i = 1; $i <= $c; $i++) {
                    echo $this->print_td($i);
                }
                echo $this->print_month_row(1, ' &gt; &gt;');
                ?>

            </tr>
        </table>
        <div id="streak" class="text-right">Max streak <?= $this->max_streak ?>.</div>
    <?php
    }

    public function print_month_row($i, $txt)
    {
        $link = F::link($this->date->month_offset($i));
        ?>
        <td><a href="/?date=<?= $link ?>">
                <?= $txt ?></a></td>
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
                    ><?=  $this->get_cur_text_entry(false) ?></textarea>
            </form>
        <?php
        } else {
            ?>
            <div id="entry_body">
                <?=  $this->get_cur_text_entry() ?>
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

    private function print_td($i)
    {
        $d = $this->date->get_date_for($i);
        $d_str = (string)$d;
        $img_src = '/resources/';
        $title = $d_str . ' : ';
        $href = "javascript:void(0)";


        if (array_key_exists($d_str, $this->existing_words)) {
            //$count = str_word_count($this->sanitize(strip_tags($this->existing_words[$d_str])));
            $count =$this->count_words($this->existing_words[$d_str]);
            $img_src .= $count >= 750 ? '2-points.png' : '1-point.png';
            $title .= $count . ' words!';
            $href = "/?date=" . $d_str;

        } elseif ($d->is_future()) { // future entry
            $img_src .= 'future-points.png';
            $title .= 'not available yet';

        } else {
            $img_src .= 'no-points.png';
            $title .= 'you missed this day!';
        }

        if ($d->is_now()) {
            if (!$this->date->is_now()) {
                $href = "/?date=" . $d_str;
            }
            $title = "TODAY";
        }

        ?>
        <td>
            <a href="<?= $href ?>">
                <img src="<?= $img_src ?>" height="30"
                     data-toggle="tooltip" data-placement="bottom" title="<?= $title ?>">
            </a>
        </td>
    <?php

    }

    private function count_words($string) {
        $st = str_replace('\n', ' ', strip_tags($string));
        $count = count(preg_split('/\s+/', trim($st)));

        if($count > 750){
            $this->streak += 1;
        }else{
            if($this->streak > $this->max_streak)
                $this->max_streak = $this->streak;
            $this->streak = 0;
        }

        return $count;
    }
}