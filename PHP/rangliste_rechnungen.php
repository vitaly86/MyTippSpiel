<?php
require_once "../config.php";

if (isset($_SESSION['event_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $host = new Host($conn);
    $event = new Event($conn);
    $user = new User($conn);
    $spiel = new Spiel($conn);
    $tippGenau = new TippGenau($conn);
    $tippTendenz = new TippTendenz($conn);
    $result = new Result($conn);
    $id_event = $_SESSION['event_id'];
    $event->retrieveHost($id_event);
    $id_host = $event->retainHost();
    $host->initHostPunkte($id_host);
    $host_punkte = $host->getHostPunkte();
    $user_event_conn = $user->get_UserEvent();
    $user_event_conn->initEventUsers($id_event);
    $ids_users = $user_event_conn->getUsersId();
    $spiel->initSpieleResults($id_event);
    $spiele_data = $spiel->getSpieleResults();
    $ids_spiele = $spiele_data['spiel_id'];

    $tordiff = $host_punkte['p_tordiff'];
    $winnloss = $host_punkte['p_winnloss'];
    $equality = $host_punkte['p_equality'];
    foreach ($ids_users['event_users'] as $id_user) {
        $total_score_user = [];
        $total_user_score = 0;
        for ($i = 0; $i < count($spiele_data['spiel_id']); $i++) {
            $score_user = [];
            $user_score = 0;
            $id_spiel = $spiele_data['spiel_id'][$i];
            $result_A = $spiele_data['result_A'][$i];
            $result_B = $spiele_data['result_B'][$i];
            $data = [$id_spiel, $id_user];
            $tippGenau->initGenauTipp($data);
            $tipp_genau_val = $tippGenau->getTippGenau();
            if (abs($result_A - $result_B) == $tipp_genau_val) {
                $user_score += $tordiff;
            } else {
                $user_score += 0;
            }
            $tippTendenz->initTendenzTipp($data);
            $tipp_tendenz_val = $tippTendenz->getTippTendenz();
            $val_team_A = $tipp_tendenz_val['tipp_teamA'];
            $val_team_B = $tipp_tendenz_val['tipp_teamB'];
            if (($result_A > $result_B) && ($val_team_A == "sieg") && ($val_team_B == "niederlage")) {
                $user_score += $winnloss;
            } else if (($result_A < $result_B) && ($val_team_A == "niederlage") && ($val_team_B == "sieg")) {
                $user_score += $winnloss;
            } else if (($result_A == $result_B) && ($val_team_A == "unentschieden") && ($val_team_B == "unentschieden")) {
                $user_score += $equality;
            }
            $score_user = array(
                'result_A' => $result_A,
                'result_B' => $result_B,
                'tipp_A' => $val_team_A,
                'tipp_B' => $val_team_B,
                'spiel_genau' => abs($result_A - $result_B),
                'tipp_genau' => $tipp_genau_val,
                'ergebniss' => $user_score
            );
            $total_user_score += $user_score;
            // echo "<br>";
            // print_r($score_user);
        }
        $total_score_user = array(
            'user_id' => $id_user,
            'ergebniss' => $total_user_score
        );
        // echo "<br><br>Total Score user<br>";
        // print_r($total_score_user);
        // echo "<br>";

        if ($result->table_not_empty()) {
            $curr_data = array(
                'urid' => $id_user,
                'evrid' => $id_event
            );
            if ($result->not_exists($curr_data)) {
                $result_data = [$id_user, $id_event, $total_user_score];
                $result->insert($result_data);
            } else {
                echo "";
            }
        } else {
            $result_data = [$id_user, $id_event, $total_user_score];
            $result->insert($result_data);
        }
    }
}
