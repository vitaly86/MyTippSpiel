<?php
session_start();
include '../config.php';

print_r($_SESSION);
if (isset($_SESSION['host_name'])) {
    $es = "Abgemeldet!&host=OK";
} else {
    $es = "Abgemeldet!";
}

session_unset();
session_destroy();

Util::redirect("../index.php", "success", $es);
