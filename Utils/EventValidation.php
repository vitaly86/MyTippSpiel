<?php
class EventValidation
{
    public static function clean($str)
    {
        $str = trim($str);
        $str = stripcslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    public static function validate($str)
    {
        # Letters and digits only
        $name_regex = "/^([a-zA-Z0-9 ]+)$/";
        if (preg_match($name_regex, $str))
            return true;
        else
            return false;
    }
}
