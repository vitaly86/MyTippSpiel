<?php

class ErgebnissValidation
{
    public static function clean($str)
    {
        $str = trim($str);
        $str = stripcslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    public static function teamResult($str)
    {
        # Digits only
        $name_regex = "/^([0-9]{1,2})$/";
        if (preg_match($name_regex, $str))
            return true;
        else
            return false;
    }
}
