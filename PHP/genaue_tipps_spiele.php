<?php
session_start();
require_once "../config.php";

if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $db = new Database();
    $conn = $db->connect();

    $user = new User($conn);
    $user_event = $user->get_UserEvent();
    $spiel = new Spiel($conn);
    $tippgenau = new TippGenau($conn);
    $id_event = $_SESSION['event_id'];
    $user_event->initEventUsers($id_event);
    $user_event_ids = $user_event->getUsersId();
    $users_dispo = count($user_event_ids['event_users']);
    foreach ($user_event_ids['event_users'] as $user_id) {
        $user->initUser($user_id);
        $user_data = $user->getUser();
    }
    $spiel->initSpieleResults($id_event);
    $spiele_data = $spiel->getSpieleResults();
    $users_ids = $user_event_ids['event_users'];
    $spiele_ids = $spiele_data['spiel_id'];

    foreach ($users_ids as $user_id) {
        foreach ($spiele_ids as $spiel_id) {
            $init_tipp_data = [$spiel_id, $user_id];
            $tippgenau->initGenauTipp($init_tipp_data);
            $tippgenau_data = $tippgenau->getTippGenauAll();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/spiele_genaue_tipps.css">
    <title>Genaue Tipps</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="ergebnisse_event.php?event_id=<?php echo $id_event; ?>">Event Seite</a>
        <a href="tendenz_tipps_spiele.php?event_id=<?php echo $id_event; ?>">Tendenz Tipps</a>
    </div>
    <h1>Genaue Tipps</h1>
    <hr>
    <div class="container">
        <div class="tipp_genau_info">
            <?php
            $users_ids = $user_event_ids['event_users'];
            foreach ($users_ids as $user_id_key => $user_id_value) {
                $user->initUser($user_id_value);
                $user_data = $user->getUser(); ?>
                <h2><?php echo $user_data['user_name']; ?> </h2>
                <table>
                    <tr>
                        <th>Spiel</th>
                        <th>Tordifferenz</th>
                        <th>Datum</th>
                    </tr>
                    <?php
                    for ($i = 0; $i < count($spiele_data['spiel_id']); $i++) {
                        $spiel_id = $spiele_data['spiel_id'][$i];
                        $spiel_name = $spiele_data['spiel_name'][$i];
                        $init_tipp_data = [$spiel_id, $user_id_value];
                        $tippgenau->initGenauTipp($init_tipp_data);
                        $tippgenau_data = $tippgenau->getTippGenauAll();
                        $tipp_val = $tippgenau_data['tordiff'];
                        $tipp_datum = $tippgenau_data['datum'];
                    ?>
                        <tr>
                            <td><?php echo $spiel_name; ?> </td>
                            <td><?php echo $tipp_val; ?></td>
                            <td><?php echo $tipp_datum; ?></td>
                        </tr>
                    <?php
                    } ?>
                </table>
                <?php if ($user_id_key < $users_dispo - 1) {
                ?>
                    <div>
                        <hr>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <div id="hr">
            <hr>
        </div>
    </div>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>