<?php

class Util
{
    public static function redirect($location, $type, $msg, $data = "")
    {
        header("Location: $location?$type=$msg&$data");
        exit();
    }
}
