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
    const FORMAT = 'Y-m-d';
    private $date;

    public function __construct($date_str = 'now')
    {
        $this->date = strtotime($date_str);
    }


    public function get_date_for($i, $as_text = false)
    {
        $d = mktime(0, 0, 0, $this->get_month(), $i, $this->get_year());
        return $as_text ? MyDate::format($d) : $d;
    }

    public function month_offset($i, $as_text = false)
    {
        $d = mktime(0, 0, 0, $this->get_month() + $i, 1, $this->get_year());
        return $as_text ? MyDate::format($d) : $d;
    }

    public
    static function format($d)
    {
        return date(MyDate::FORMAT, $d);
    }

    public function get_month()
    {
        return date('n', $this->date);
    }

    public function get_nbr_of_days()
    {
        return date('t', $this->date);
    }

    public function get_day()
    {
        return date('d', $this->date);
    }

    public function get_year()
    {
        return date('Y', $this->date);
    }

    public function to_string()
    {
        return MyDate::format($this->date);
    }

    public function get()
    {
        return $this->date;
    }
}