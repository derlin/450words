<?php
/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 14/06/15
 * Time: 12:41
 */
include_once 'utils/db.php';
include_once 'formatter.php';


class MyDate
{
    private $date, $day, $month, $year;

    private function __construct($date)
    {
        $this->date = $date;
        $this->day = date('d', $this->date);
        $this->month = date('m', $this->date);
        $this->year = date('Y', $this->date);
    }

    // ---------------------------

    public static function from_string($str = 'now'){
        return new MyDate(strtotime($str));
    }

    public static function from_date($d){
        return new MyDate($d);
    }

    // ---------------------------

    public function get_date_for($i)
    {
        return new MyDate(mktime(0, 0, 0, $this->month, $i, $this->year));
    }

    public function month_offset($i)
    {
        return new MyDate(mktime(0, 0, 0, $this->month + $i, 1, $this->year));
    }

    public function get_nbr_of_days()
    {
        return date('t', $this->date);
    }


    public function is_now(){
        return  F::link($this->date) == F::link(strtotime('now'));
    }

    public function is_future(){
        return F::link($this->date) > F::link(strtotime('now'));
    }

    public function get()
    {
        return $this->date;
    }

    public function __toString()
    {
        return F::link($this->date);
    }
}