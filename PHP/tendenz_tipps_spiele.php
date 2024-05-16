<?php
session_start();
require_once "../config.php";

if (isset($_GET['spiel_id']) && !empty($_GET['spiel_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $user_event = $user->get_UserEvent();
    $spiel = new Spiel($conn);
    $tipptendenz = new TippTendenz($conn);
    $id_event = $_SESSION['event_id'];
    $id_spiel = $_GET['spiel_id'];
    $user_event->initEventUsers($id_event);
    $user_event_ids = $user_event->getUsersId();
    $spiel->init_one_Spiel($id_spiel);
    $spiel_data = $spiel->get_one_SpielData();
    $spiel_name = $spiel_data['spiel_name'];
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/spiele_tendenz_tipps.css">
    <title>Tendenz Tipps</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="genaue_tipps_spiele.php?event_id=<?php echo $id_event; ?>&spiel_id=<?php echo $id_spiel; ?>">Genaue Tipps</a>
    </div>
    <h1><?php echo $spiel_name; ?></h1>
    <h2>Tendenz Tipps</h2>
    <hr>
    <div class="container">
        <div class="tipp_tendenz_info">
            <table>
                <tr>
                    <th>User</th>
                    <th>Team A</th>
                    <th>Team B</th>
                    <th>Datum</th>
                </tr>
                <?php
                $users_ids = $user_event_ids['event_users'];
                foreach ($users_ids as $id_user) {
                    $user->initUser($id_user);
                    $user_data = $user->getUser();
                    $init_tipp_data = [$id_spiel, $id_user];
                    $tipptendenz->initTendenzTipp($init_tipp_data);
                    $tipptendenz_data = $tipptendenz->getTippTendenzAll();
                    $user_name = $user_data['user_name'];
                    $tipp_A_val = $tipptendenz_data['tipp_teamA'];
                    $tipp_B_val = $tipptendenz_data['tipp_teamB'];
                    $tipp_datum = $tipptendenz_data['datum'];
                ?>
                    <tr>
                        <td><?php echo $user_name; ?> </td>
                        <td><?php echo $tipp_A_val; ?></td>
                        <td><?php echo $tipp_B_val; ?></td>
                        <td><?php echo $tipp_datum; ?></td>
                    </tr>
                <?php
                } ?>
            </table>
        </div>
        <hr>
    </div>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>