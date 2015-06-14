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

    private $date;
    private $existing_words = array();

    public function __construct($mydate)
    {
        $this->date = $mydate;

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
    <?php
    }

    public function print_month_row($i, $txt)
    {
        $link = F::link($this->date->month_offset($i));
        ?>
        <td><a href="/protectedpage.php?date=<?= $link ?>">
                <?= $txt ?></a></td>
    <?php
    }

    public function print_text_area()
    {
        $text = $this->get_cur_text_entry();

        if ($this->date->is_now()) {
            ?>
            <form method="post" action="/autosave.php">
                <textarea cols="40"
                          id="entry_body"
                          name="entry_body" rows="20"
                    ><?= $text ?></textarea>
                <button class="btn btn-primary" type="submit" />
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


    public function get_cur_text_entry()
    {
        $key = F::link($this->date);
        return array_key_exists($key, $this->existing_words) ?
            $this->existing_words[$key] : "";
    }

    private function print_td($i)
    {
        $d = $this->date->get_date_for($i);
        $d_str = (string)$d;
        $img_src = '/resources/';
        $title = $d_str . ' : ';
        $href = "javascript:void(0)";


        if (array_key_exists($d_str, $this->existing_words)) {
            $count = str_word_count(strip_tags($this->existing_words[$d_str]));
            $img_src .= $count >= 750 ? '2-points.png' : '1-point.png';
            $title .= $count . ' words!';
            $href = "protectedpage.php?date=" . $d_str;

        } elseif ($d->is_future()) { // future entry
            $img_src .= 'future-points.png';
            $title .= 'not available yet';

        } else {
            $img_src .= 'no-points.png';
            $title .= 'you missed this day!';
        }

        if ($d->is_now()) {
            if (!$this->date->is_now()) {
                $href = "protectedpage.php?date=" . $d_str;
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