<?php

class SpielValidation
{
    public static function clean($str)
    {
        $str = trim($str);
        $str = stripcslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    public static function spielName($str)
    {
        # Letters only
        $name_regex = "/^([a-zA-Z0-9:,.\- ]+)$/";
        if (preg_match($name_regex, $str))
            return true;
        else
            return false;
    }

    public static function spielDatum($datetime)
    {
        # A valid datetime must be in the format 
        # YYYY-MM-DD HH:MM:SS.
        $format = 'Y-m-d\TH:i';
        $dateTimeObj = DateTime::createFromFormat($format, $datetime);
        // return $dateTimeObj !== false && !array_sum($dateTimeObj->getLastErrors());
        return $dateTimeObj !== false;
    }
}
