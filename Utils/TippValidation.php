<?php

class TippValidation
{
    public static function clean($str)
    {
        $str = trim($str);
        $str = stripcslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }

    public static function tippGenau($str)
    {
        # Digits only
        $name_regex = "/^([0-9]{1,2})$/";
        if (preg_match($name_regex, $str))
            return true;
        else
            return false;
    }

    public static function choseTeam($teamA, $teamB, $option1, $option2)
    {
        if (!isset($teamA) && !isset($teamB) && (isset($option1) || isset($option2))) {
            return false;
        } else if ((isset($teamA) && (isset($option1) || isset($option2)))) {
            return true;
        } else if ((isset($teamB) && (isset($option1) || isset($option2)))) {
            return true;
        } else {
            return true;
        }
    }

    public static function tippError1($teamA, $teamB, $option1, $option2)
    {
        if (isset($teamA) && isset($teamB) && (isset($option1) || isset($option2))) {
            return false;
        } else if ((isset($teamA) && (isset($option1) || isset($option2)))) {
            return true;
        } else if ((isset($teamB) && (isset($option1) || isset($option2)))) {
            return true;
        } else {
            return true;
        }
    }

    public static function tippError2($teamA, $teamB, $option)
    {
        if (isset($option)) {
            if (!isset($teamA) && !isset($teamB)) {
                return false;
            } else if (isset($teamA) && !isset($teamB)) {
                return false;
            } else if (!isset($teamA) && isset($teamB)) {
                return false;
            } else {
                return true;
            }
        } else return true;
    }


    public static function tippDatum($datetime)
    {
        # A valid datetime must be in the format 
        # YYYY-MM-DD HH:MM:SS.
        $format = 'Y-m-d\TH:i';
        $dateTimeObj = DateTime::createFromFormat($format, $datetime);
        // return $dateTimeObj !== false && !array_sum($dateTimeObj->getLastErrors());
        return $dateTimeObj !== false;
    }

    public static function getData($teamA, $teamB, $option1, $option2, $option3)
    {
        if (isset($option1)) {
            if (isset($teamA)) {
                $tipp_data = ['sieg', 'niederlage'];
            } else if (isset($teamB)) {
                $tipp_data = ['niederlage', 'sieg'];
            }
        } else if (isset($option2)) {
            if (isset($teamA)) {
                $tipp_data = ['niederlage', 'sieg'];
            } else if (isset($teamB)) {
                $tipp_data = ['sieg', 'niederlage'];
            }
        } else if (isset($option3)) {
            $tipp_data = ['unentschieden', 'unentschieden'];
        }
        return $tipp_data;
    }

    public static function choseAction($insert, $update)
    {
        if (!isset($insert) && !isset($update)) {
            return false;
        } else return true;
    }

    public static function choseTipp($tipp1, $tipp2, $tipp3)
    {
        if (!isset($tipp1) && !isset($tipp2) && !isset($tipp3)) {
            return false;
        } else return true;
    }
}
