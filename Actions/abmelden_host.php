<?php
session_start();
include '../config.php';

print_r($_SESSION);
if (isset($_SESSION['host_id']) && isset($_SESSION['host_name'])) {
    $es = "Abgemeldet!&host=OK";
    unset($_SESSION['host_id']);
    unset($_SESSION['host_name']);
    Util::redirect("../index.php", "success", $es);
}
