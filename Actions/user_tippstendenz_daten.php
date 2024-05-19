<?php
session_start();
include '../config.php';

print_r($_SESSION);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    print_r($_POST);
    $insert = $_POST['op_insert'];
    $update = $_POST['op_update'];

    $team_A = $team_B = $sieg = $niederlage = $unentschieden = null;

    if (isset($_POST['op_teamA'])) {
        $team_A = $_POST['op_teamA'];
    }

    if (isset($_POST['op_teamB'])) {
        $team_B = $_POST['op_teamB'];
    }

    if (isset($_POST['t_sieg'])) {
        $sieg = $_POST['t_sieg'];
    }

    if (isset($_POST['t_niederlage'])) {
        $niederlage = $_POST['t_niederlage'];
    }

    if (isset($_POST['t_unentschieden'])) {
        $unentschieden = $_POST['t_unentschieden'];
    }

    $tipp_datum = TippValidation::clean($_POST['t_datum']);

    $data = "tdatum=" . $tipp_datum  . "&spiel_id=" . $_SESSION['spiel_id'];

    if (!TippValidation::choseTeam($team_A, $team_B, $sieg, $niederlage)) {
        // echo "<br>Two teams cannot win or lose at the same time";
        $em = "You have to choose a team to win or lose";
        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
    } else if (!TippValidation::tippError1($team_A, $team_B, $sieg, $niederlage)) {
        $em = "Two teams in the same game cannot both win or lose";
        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
    } else if (!TippValidation::tippError2($team_A, $team_B, $unentschieden)) {
        $em = "You have to check both teams";
        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
    } else if (!TippValidation::tippDatum($tipp_datum)) {
        $em = "Invalid datetime format";
        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
    } else if (!TippValidation::choseTipp($sieg, $niederlage, $unentschieden)) {
        $em = "You must chose a Tipp: [Sieg/Niederlage/Unentschieden]?";
        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
    } else if (!TippValidation::choseAction($insert, $update)) {
        $em = "You must chose an action: [Insert/Update]?";
        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $tipp = new TippTendenz($conn);
        $spiel = new Spiel($conn);
        $user_id = $_SESSION['user_id'];
        $spiel_id = $_SESSION['spiel_id'];
        $spiel->init_one_Spiel($spiel_id);
        $tipp_valid = $spiel->get_ValidDatum($tipp_datum);
        if (!$tipp_valid) {
            $em = "Game already started! No more Tipps!";
            Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
        } else {
            if ($tipp->exists()) {
                if (isset($_POST['op_insert']) && !empty($_POST['op_insert'])) {
                    $spiel_id = $_SESSION['spiel_id'];
                    $user_id = $_SESSION['user_id'];
                    if ($tipp->not_Inserted($spiel_id, $user_id)) {
                        $user_data = [$spiel_id, $user_id, $tipp_datum];
                        $spiel_data = TippValidation::getData($team_A, $team_B, $sieg, $niederlage, $unentschieden);
                        $tipp_data = array_merge($user_data, $spiel_data);
                        $res = $tipp->insert($tipp_data);
                        if ($res) {
                            $sm = "Your Tipp is successfully registered!";
                            Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_success", $sm, $data);
                        } else {
                            $em = "An error occurred";
                            Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
                        }
                    } else {
                        $em = "The tipp already exists. You can still Update.";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
                    }
                } else if (isset($_POST['op_update']) && !empty($_POST['op_update'])) {
                    $spiel_id = $_SESSION['spiel_id'];
                    $user_id = $_SESSION['user_id'];
                    if (!$tipp->not_Inserted($spiel_id, $user_id)) {
                        $spiel_data = TippValidation::getData($team_A, $team_B, $sieg, $niederlage, $unentschieden);
                        $tipp_data = [
                            'tippAteam' => $spiel_data[0],
                            'tippBteam' => $spiel_data[1],
                            'tippdatum' => $tipp_datum
                        ];
                        $res = $tipp->update($tipp_data, $spiel_id, $user_id);
                        if ($res) {
                            $sm = "Your Tipp is successfully updated!";
                            Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_success", $sm, $data);
                        } else {
                            $em = "The tipp was not updated";
                            Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
                        }
                    } else {
                        $em = "Sorry, the tipp is not inserted";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
                    }
                }
            } else {
                if (isset($_POST['op_insert']) && !empty($_POST['op_insert'])) {
                    $spiel_id = $_SESSION['spiel_id'];
                    $user_id = $_SESSION['user_id'];
                    $user_data = [$spiel_id, $user_id, $tipp_datum];
                    $spiel_data = TippValidation::getData($team_A, $team_B, $sieg, $niederlage, $unentschieden);
                    $tipp_data = array_merge($user_data, $spiel_data);
                    $res = $tipp->insert($tipp_data);
                    if ($res) {
                        $sm = "Your Tipp is successfully registered!";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_success", $sm, $data);
                    } else {
                        $em = "The tipp was not registered";
                        Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
                    }
                } else if (isset($_POST['op_update']) && !empty($_POST['op_update'])) {
                    $em = "Before update you have to insert it!";
                    Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em, $data);
                }
            }
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/abgeben_tipps.php", "t_tendenz_error", $em);
}
