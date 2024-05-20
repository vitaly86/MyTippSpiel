<?php
session_start();
include '../config.php';

print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
    $tordiff = HostEinstellungValidation::clean($_POST['tordiff']);
    $winnloss = HostEinstellungValidation::clean($_POST['stimmt']);
    $equality = HostEinstellungValidation::clean($_POST['gleich']);
    $host_id = $_SESSION['host_id'];
    $host_name = $_SESSION['host_name'];

    if (!HostEinstellungValidation::tippScore($tordiff)) {
        $em = "Punkte für genauer Tipp is invalid";
        Util::redirect("../PHP/einstellungen-host.php", "error", $em, $data);
    } else if (!HostEinstellungValidation::tippScore($winnloss)) {
        $em = "Übereinstimmen Punkte ist invalid";
        Util::redirect("../PHP/einstellungen-host.php", "error", $em, $data);
    } else if (!HostEinstellungValidation::tippScore($equality)) {
        $em = "Unentschieden Punkte ist invalid";
        Util::redirect("../PHP/einstellungen-host.php", "error", $em, $data);
    } {
        $db = new Database();
        $conn = $db->connect();
        $einstellungen = new Einstellung($conn);
        $init_data = [$host_id, $host_name, NULL, NULL, NULL];
        $einstellungen_data = [
            'tordiff' => $tordiff,
            'winnloss' => $winnloss,
            'equality' => $equality
        ];
        $res = $einstellungen->insert($einstellungen_data, $host_id, $host_name);
        if ($res) {
            $sm = "Einstellungen sind erfolgreich gespeichert!";
            Util::redirect("../PHP/einstellungen-host.php", "success", $sm, $data);
        } else {
            $em = "Einstellungen wurde nicht gespeichert";
            Util::redirect("../PHP/einstellungen-host.php", "error", $em, $data);
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/einstellungen-host.php", "error", $em);
}
