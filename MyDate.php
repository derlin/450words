<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 14/06/15
 * Time: 12:41
 */
include_once 'utils/db.php';

class MyDate
{
    private $cur_date;
    private $date, $month, $year;
    private $existing_words = array();

    public function __construct($date_str = 'now')
    {
        $this->cur_date = strtotime('now');
        $this->date = strtotime($date_str);
        $this->month = date('n', $this->date);
        $this->year = date('Y', $this->date);

        echo '-->' . MyDate::text_format($this->date) . '  ';

        $mysqli = dbConnect("words");

        $sql = $mysqli->prepare('select day, word from words where userid = ? and day between ? and ?;');
        $sql->bind_param('sss', $_SESSION['uid'], date('Y-m-1', $this->date), date('Y-m-31', $this->date));
        $sql->bind_result($day, $text);
        $sql->execute();
        while ($row = $sql->fetch()) {
            $this->existing_words[$day] = $text;
        }
        $sql->close();
    }


    public function get_image($i)
    {
        $d = MyDate::link_format($this->get_date_for($i), true);
        if (array_key_exists($d, $this->existing_words)) {
            $count = str_word_count(strip_tags($this->existing_words[$d]));
            return $count >= 750 ? '/resources/2-points.png' : '/resources/1-point.png';
        }
        return $d > $this->cur_date ? '/resources/future-points.png' : '/resources/no-points.png';
    }

    public function get_date_for($i, $as_text = false)
    {
        $d = mktime(0, 0, 0, $this->month, $i, $this->year);
        return $as_text ? MyDate::link_format($d) : $d;
    }

    public function add_month($i, $as_text = false){
        $d = $d = mktime(0, 0, 0, $this->month + $i, 1, $this->year);
        return $as_text ? MyDate::link_format($d) : $d;
    }

    public
    static function link_format($d)
    {
        return date('Y-m-d', $d);
    }

    public static function text_format($d)
    {
        return date('Y-m-d', $d);
    }

}