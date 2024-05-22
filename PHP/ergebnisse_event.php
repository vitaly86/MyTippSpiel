<?php
session_start();
require_once "../config.php";

if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $host = new Host($conn);
    $event = new Event($conn);
    $spiel = new Spiel($conn);
    $spiel_ex = new Spiel($conn);
    $_SESSION['event_id'] = $_GET['event_id'];
    $id_event = $_SESSION['event_id'];
    $host->initHost($id_event);
    $event->initEventSpiele($id_event);
    $spiel->initSpieleResults($id_event);
    $spiel_ex->initSpieleExpired($id_event);
    $event_data = $event->getEventSpiele();
    $id_host = $event_data['event_host'];
    $host->initHost($id_host);
    $host_data = $host->getHost();
    $spiele_data = $spiel->getSpieleResults();
    $spiele_data_ex = $spiel_ex->getSpieleResults();
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/event-ergebnisse.css">
    <title>Gespielte Spiele</title>
</head>

<body>
    <div class="top-bar">
        <h1>Willkommen zum Sport-Event</h1>
        <div id="header"></div>
    </div>
    <div class="entry">
        <a href="homepage-user.php?event_id=<?php echo $id_event; ?>">Startseite</a>
        <?php if (isset($_GET['zeitraum']) && $_GET['zeitraum'] == 'innerhalb') {
            echo "";
        } else { ?>
            <a href="rangliste_users.php?event_id=<?php echo $id_event; ?>">Rangliste</a>
        <?php } ?>
    </div>
    <h1><?php echo $event_data['event_name']; ?></h1>
    <div class="container">
        <div class="event_info">
            <div class="host-mess">
                <div id="host">Host: <?php echo $host_data['host_fullname']; ?></div>
                <?php if (isset($_GET['error']) && !empty($_GET['error'])) { ?>
                    <p class="error"><?php echo $_GET['error']; ?> </p>
                <?php } ?>
            </div>
            <div id="sep-host"></div>
            <div class="current-info">
                <?php if (isset($_GET['zeitraum']) && $_GET['zeitraum'] == 'innerhalb') { ?>
                    <div class="user-info">
                        <div class="details">Abgegebene Tipps:</div>
                        <div class="spiele-container">
                            <?php
                            for ($i = 0; $i < count($spiele_data_ex['spiel_id']); $i++) {
                                $spiel_id = $spiele_data_ex['spiel_id'][$i];
                                $spiel_name = $spiele_data_ex['spiel_name'][$i];
                            ?>
                                <div class="spiele">
                                    <span><?php echo $i + 1; ?>. </span>
                                    <a href="genaue_tipps_spiele.php?event_id=<?php echo $id_event; ?>&spiel_id=<?php echo $spiel_id; ?>&zeitraum=innerhalb"><?php echo $spiel_name; ?> </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="sep-info"></div>
                    <div class="host-info">
                        <div class="details">Spiele Ergebnisse</div>
                        <table>
                            <tr>
                                <th>Spiel Id</th>
                                <th>Team A Result</th>
                                <th>Team B Result</th>
                                <th>Spiel Datum</th>
                            </tr>
                            <?php foreach ($spiele_data_ex['spiel_id'] as $key => $result) {
                                $teamA_result = $spiele_data_ex['result_A'][$key];
                                $teamB_result = $spiele_data_ex['result_B'][$key];
                                $spiel_datum = $spiele_data_ex['spiel_datum'][$key];
                            ?>
                                <tr>
                                    <td><?php echo $key + 1; ?> </td>
                                    <td class="result"><?php echo $teamA_result; ?></td>
                                    <td class="result"><?php echo $teamB_result; ?></td>
                                    <td class="result"><?php echo $spiel_datum; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php  } else { ?>
                    <div class="user-info">
                        <div class="details">Abgegebene Tipps:</div>
                        <div class="spiele-container">
                            <?php for ($i = 0; $i < count($spiele_data['spiel_id']); $i++) {
                                $spiel_id = $spiele_data['spiel_id'][$i];
                                $spiel_name = $spiele_data['spiel_name'][$i];
                            ?>
                                <div class="spiele">
                                    <span><?php echo $i + 1; ?>. </span>
                                    <a href="genaue_tipps_spiele.php?event_id=<?php echo $id_event; ?>&spiel_id=<?php echo $spiel_id; ?>"><?php echo $spiel_name; ?> </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div id="sep-info"></div>
                    <div class="host-info">
                        <div class="details">Spiele Ergebnisse</div>
                        <table>
                            <tr>
                                <th>Spiel Id</th>
                                <th>Team A Result</th>
                                <th>Team B Result</th>
                                <th>Spiel Datum</th>
                            </tr>
                            <?php foreach ($spiele_data['spiel_id'] as $key => $result) {
                                $teamA_result = $spiele_data['result_A'][$key];
                                $teamB_result = $spiele_data['result_B'][$key];
                                $spiel_datum = $spiele_data['spiel_datum'][$key];
                            ?>
                                <tr>
                                    <td><?php echo $key + 1; ?> </td>
                                    <td class="result"><?php echo $teamA_result; ?></td>
                                    <td class="result"><?php echo $teamB_result; ?></td>
                                    <td class="result"><?php echo $spiel_datum; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    </div>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>