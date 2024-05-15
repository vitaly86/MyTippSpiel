<?php
session_start();
include '../config.php';

print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
    $tipp_score = TippValidation::clean($_POST['tordiff']);
    $tipp_datum = TippValidation::clean($_POST['t_datum']);

    $data = "score=" . $tipp_score . "&sdatum=" . $tipp_datum . "&spiel_id=" . $_SESSION['spiel_id'];

    if (!TippValidation::tippGenau($tipp_score)) {
        $em = "Your score is incorrect";
        Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
    } else if (!TippValidation::tippDatum($tipp_datum)) {
        $em = "Invalid datetime format";
        Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $tipp = new TippGenau($conn);
        if ($tipp->exists()) {
            if (isset($_POST['op_insert']) && !empty($_POST['op_insert'])) {
                $spiel_id = $_SESSION['spiel_id'];
                $user_id = $_SESSION['user_id'];
                if ($tipp->not_Inserted($spiel_id, $user_id)) {
                    $tipp_data = [$spiel_id, $user_id, $tipp_score, $tipp_datum];
                    $res = $tipp->insert($tipp_data);
                    if ($res) {
                        $sm = "Your Tipp is successfully registered!";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_genau_success", $sm, $data);
                    } else {
                        $em = "An error occurred";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
                    }
                } else {
                    $em = "The tipp already exists. You can still Update.";
                    Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
                }
            } else if (isset($_POST['op_update']) && !empty($_POST['op_update'])) {
                $spiel_id = $_SESSION['spiel_id'];
                $user_id = $_SESSION['user_id'];
                if (!$tipp->not_Inserted($spiel_id, $user_id)) {
                    $tipp_data = [
                        'tipptordiff' => $tipp_score,
                        'tippdatum' => $tipp_datum
                    ];
                    $res = $tipp->update($tipp_data, $spiel_id, $user_id);
                    if ($res) {
                        $sm = "Your Tipp is successfully updated!";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_genau_success", $sm, $data);
                    } else {
                        $em = "The tipp was not updated";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
                    }
                } else {
                    $em = "Sorry, the tipp is not inserted";
                    Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
                }
            }
        } else {
            if (isset($_POST['op_insert']) && !empty($_POST['op_insert'])) {
                $spiel_id = $_SESSION['spiel_id'];
                $user_id = $_SESSION['user_id'];
                $tipp_data = [$spiel_id, $user_id, $tipp_score, $tipp_datum];
                $res = $tipp->insert($tipp_data);
                if ($res) {
                    $sm = "Your Tipp is successfully registered!";
                    Util::redirect("../PHP/abgeben_tipps.php", "t_genau_success", $sm, $data);
                } else {
                    $em = "The tipp was not registered";
                    Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
                }
            } else if (isset($_POST['op_update']) && !empty($_POST['op_update'])) {
                $em = "Before update you have to insert data!";
                Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em, $data);
            }
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/abgeben_tipps.php", "t_genau_error", $em);
}
