<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
    $spiel_name = SpielValidation::clean($_POST['sname']);
    $spiel_datum = SpielValidation::clean($_POST['sdatum']);

    $data = "sname=" . $spiel_name . "&sdatum=" . $spiel_datum . "&event_id=" . $_SESSION['event_id'];

    if (!SpielValidation::spielName($spiel_name)) {
        $em = "Try a valid Name Game";
        Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
    } else if (!SpielValidation::spielDatum($spiel_datum)) {
        $em = "Invalid datetime format";
        Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $spiel = new Spiel($conn);
        $event_id = $_SESSION['event_id'];
        $spiel->find_min_SpielDatum($event_id);
        $spiel->find_max_SpielDatum($event_id);
        $check_ende = $spiel->checkedAbgelaufenEvent($spiel_datum);
        $check_beginn = $spiel->checkedLaufenEvent($spiel_datum);
        $check_valid = $spiel->checkedBeginnEvent($spiel_datum);
        if ($check_ende) {
            $em = "Sorry, the Event is expired!";
            Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
        } else if ($check_beginn) {
            $em = "Sorry, the Event already begun! No more games!";
            Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
        } else if (!$check_valid) {
            $em = "Sorry, your Game date is in the past!";
            Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
        } else {
            if ($spiel->exists()) {
                if ($spiel->is_SpielUnique($spiel_name, $event_id)) {
                    $spiel_data = [$event_id, $spiel_name, $spiel_datum];
                    $res = $spiel->insert($spiel_data);

                    if ($res) {
                        $sm = "Successfully registered!";
                        Util::redirect("../PHP/hinzufuegen_spiele.php", "success", $sm, $data);
                    } else {
                        $em = "The game was not registered";
                        Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
                    }
                } else {
                    $em = "The game already exists inside this event";
                    Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
                }
            } else {
                $event_id = $_SESSION['event_id'];
                $spiel_data = [$event_id, $spiel_name, $spiel_datum];
                $res = $spiel->insert($spiel_data);

                if ($res) {
                    echo "Successfully registered!";
                    $sm = "Successfully registered!";
                    Util::redirect("../PHP/hinzufuegen_spiele.php", "success", $sm, $data);
                } else {
                    echo "The game was not registered";
                    $em = "The game was not registered";
                    Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em, $data);
                }
            }
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/hinzufuegen_spiele.php", "error", $em);
}
