<?php

class UserValidation
{
    public static function clean($str)
    {
        $str = trim($str);
        $str = stripcslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    public static function userName($str)
    {
        # Letters and digits only
        $name_regex = "/^([a-zA-Z0-9 ]+)$/";
        if (preg_match($name_regex, $str))
            return true;
        else
            return false;
    }


    public static function userEmail($str)
    {
        if (filter_var($str, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }

    public static function userPassword($str)
    {
        # Has minimum 4 characters in lenght.
        # al least one uppercase English letter. (?=.*?[A-Z])
        # al least one lowercase English letter. (?=.*?[a-z])
        # al least one digit. (?=.*?[0-9])
        # al least one special character.. (?=.*?[#?!@$%^&*-])
        # Test@pass1
        $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,}$/";
        if (preg_match($password_regex, $str))
            return true;
        else
            return false;
    }
}
