<?php
session_start();
include '../config.php';

print_r($_SESSION);
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    $es = "Abgemeldet!&user=OK";
    unset($_SESSION['user_id']);
    unset($_SESSION['user_email']);
    Util::redirect("../index.php", "success", $es);
}
