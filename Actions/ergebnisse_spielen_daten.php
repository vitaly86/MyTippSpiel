<?php
session_start();
include '../config.php';

print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
    $erg_Team_A = ErgebnissValidation::clean($_POST['team_A_result']);
    $erg_Team_B = ErgebnissValidation::clean($_POST['team_B_result']);
    $spiel_id = $_SESSION['spiel_id'];
    $event_id = $_SESSION['event_id'];

    $data = "event_id=" . $spiel_id . "&spiel_id=" . $spiel_id . "&team_A=" . $erg_Team_A . "&team_B=" . $erg_Team_B;

    if (!ErgebnissValidation::teamResult($erg_Team_A)) {
        $em = "Team A result is invalid";
        Util::redirect("../PHP/eintragen_ergebnisse.php", "error", $em, $data);
    } else if (!ErgebnissValidation::teamResult($erg_Team_B)) {
        $em = "Team B result is invalid";
        Util::redirect("../PHP/eintragen_ergebnisse.php", "error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $ergebniss = new Ergebniss($conn);
        $init_data = [$spiel_id, $event_id, NULL, NULL];
        if ($ergebniss->not_Inserted($init_data)) {
            $ergebniss_data = [
                'teamAresult' => $erg_Team_A,
                'teamBresult' => $erg_Team_B
            ];
            $res = $ergebniss->insert($ergebniss_data, $spiel_id, $event_id);
            if ($res) {
                $sm = "Game Result is successfully inserted!";
                Util::redirect("../PHP/eintragen_ergebnisse.php", "success", $sm, $data);
            } else {
                $em = "Game result was not inserted";
                Util::redirect("../PHP/eintragen_ergebnisse.php", "error", $em, $data);
            }
        } else {
            $em = "Sorry, Game Result already inserted";
            Util::redirect("../PHP/eintragen_ergebnisse.php", "error", $em, $data);
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/eintragen_ergebnisse.php", "error", $em);
}
