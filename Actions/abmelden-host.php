<?php
session_start();
include '../config.php';

session_unset();
session_destroy();

$es = "Abgemeldet!";
Util::redirect("../index.php", "success", $es);
