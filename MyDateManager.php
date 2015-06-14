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

    private $date, $is_now;
    private $existing_words = array();

    public function __construct($date_str = 'now')
    {
        $this->date = new MyDate($date_str);
        $this->is_now = $this->date->to_string() == MyDate::format(strtotime('now'));

        $mysqli = dbConnect("words");
        $sql = $mysqli->prepare('select day, word from words where userid = ? and day between ? and ?;');
        $sql->bind_param('sss', $_SESSION['uid'], //
            MyDate::format($this->date->get_date_for(1)), //
            MyDate::format($this->date->get_date_for(31)));

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
    <?php
    }

    public function print_month_row($i, $txt)
    {
        ?>
        <td><a href="/protectedpage.php?date=<?= $this->date->month_offset($i, true) ?>">
                <?= $txt ?></a></td>
    <?php
    }

    public function print_text_area()
    {
        $text = $this->get_cur_text_entry();
        if ($this->is_now) {
            ?>
            <form method="post" action="/autosave.php">
                <textarea cols="40"
                          id="entry_body"
                          name="entry_body" rows="20"
                    ><?= $text ?></textarea>
            </form>
        <?php
        } else {
            ?>
            <div id="text_entry">
                <?= $text ?>
            </div>
        <?php
        }
    }

    public function format($format = MyDate::FORMAT)
    {
        return date($format, $this->date->get());
    }

    public function get_readable_date()
    {
        return $this->date->to_readable_string();
    }

    public function get_cur_text_entry()
    {
        $key = $this->date->to_string();
        return array_key_exists($key, $this->existing_words) ?
            $this->existing_words[$key] : "";
    }

    private function print_td($i)
    {
        $d = $this->date->get_date_for($i, true);
        $img_src = '/resources/';
        $title = $d . ' : ';
        $href = "javascript:void(0)";


        if (array_key_exists($d, $this->existing_words)) {
            $count = str_word_count(strip_tags($this->existing_words[$d]));
            $img_src .= $count >= 750 ? '2-points.png' : '1-point.png';
            $title .= $count . ' words!';
            $href = "protectedpage.php?date=" . $d;

        } elseif ($d > MyDate::format(strtotime('now'))) { // future entry
            $img_src .= 'future-points.png';
            $title .= 'not available yet';

        } else {
            $img_src .= 'no-points.png';
            $title .= 'you missed this day!';
        }

        if ($i == date('d', strtotime('now'))) {
            if (!$this->is_now) {
                $href = "protectedpage.php?date=" . $d;
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
}