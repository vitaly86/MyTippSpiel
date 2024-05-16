<?php
session_start();
include '../config.php';

if (isset($_SESSION['user_email'])) {
    $es = "Abgemeldet!" . "&user=OK";
} else {
    $es = "Abgemeldet!";
}

session_unset();
session_destroy();

Util::redirect("../index.php", "success", $es);
