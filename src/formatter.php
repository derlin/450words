<?php

/**
 * Created by PhpStorm.
 * User: lucy
 * Date: 14/06/15
 * Time: 18:48
 */

class_alias('Formatter', 'F');

class Formatter
{
    const LINK = 'Y-m-d';
    const PRETTY = 'l jS \of F Y';


    public static function link($d)
    {
        return Formatter::format(Formatter::LINK, $d);
    }

    public static function pretty($d)
    {
        return Formatter::format(Formatter::PRETTY, $d);
    }

    public static function month_nbr($d)
    {
        return Formatter::format('m', $d);
    }

    public static function month_name($d)
    {
        return Formatter::format('F', $d);
    }

    public static function month_year($d)
    {
        return Formatter::month_name($d) . ' ' . Formatter::year($d);
    }

    public static function year($d)
    {
        return Formatter::format('Y', $d);
    }

    // -----------------------------------

    private static function format($format, $d)
    {
        return date($format, is_object($d) && get_class($d) == 'MyDate' ? $d->get() : $d);

    }
}