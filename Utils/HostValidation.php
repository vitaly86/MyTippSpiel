<?php

class HostValidation
{
    public static function clean($str)
    {
        $str = trim($str);
        $str = stripcslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    public static function hostFullName($str)
    {
        # Letters only
        $name_regex = "/^([a-zA-Z ]+)$/";
        if (preg_match($name_regex, $str))
            return true;
        else
            return false;
    }

    public static function hostName($str)
    {
        # Mus start with letter [A-Za-z]
        # 6-8 characters {5,8}
        # letters and numbers only [A-Za-z0-9]

        $host_regex = "/^[a-zA-Z ][A-Za-z0-9]{5,8}$/";
        if (preg_match($host_regex, $str))
            return true;
        else
            return false;
    }

    public static function hostEmail($str)
    {
        if (filter_var($str, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }

    public static function hostPassword($str)
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
