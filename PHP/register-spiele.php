<?php
session_start();
require_once "../config.php";

if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $event = new Event($conn);
    $spiel = new Spiel($conn);
    $_SESSION['event_id'] = $_GET['event_id'];
    $id_event = $_SESSION['event_id'];
    $event->initEventSpiele($id_event);
    $spiel->initSpieleResultsExpired($id_event);
    $event_data = $event->getEventSpiele();
    $spiele_data = $spiel->getSpieleResults();
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/spiele-waehlen.css">
    <title>Gespielte Spiele</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="homepage-host.php">Startseite</a>
    </div>
    <h1 id="title"><?php echo $event_data['event_name']; ?></h1>
    <div id="bg-color">
        <div class="container">
            <ul class='list_spiele'>
                <h2>Gespielte Spiele</h2>
                <?php for ($i = 0; $i < count($spiele_data['spiel_id']); $i++) {
                    $id_spiel = $spiele_data['spiel_id'][$i];
                ?>
                    <div class="items">
                        <li>
                            <a href="eintragen_ergebnisse.php?event_id=<?php echo $id_event; ?>&spiel_id=<?php echo $id_spiel ?>">
                                <?php echo $spiele_data['spiel_name'][$i]; ?></a>
                        </li>
                    </div>
                <?php } ?>
            </ul>
            <div id="sep"></div>
            <div class="ergebnisse-info">
                <h2>Aktuelle Ergebnisse</h2>
                <table>
                    <tr>
                        <th>Spiel Id</th>
                        <th>Team A Result</th>
                        <th>Team B Result</th>
                        <th>Spiel Datum</th>
                    </tr>
                    <?php
                    foreach ($spiele_data['spiel_id'] as $key => $spiel_info) {
                        $spiel_name = $spiele_data['spiel_name'][$key];
                        $team_A_result = $spiele_data['result_A'][$key];
                        $team_B_result = $spiele_data['result_B'][$key];
                        $spiel_datum = $spiele_data['spiel_datum'][$key];
                    ?>
                        <tr>
                            <td><?php echo $key + 1; ?> </td>
                            <td class='result'><?php echo $team_A_result; ?></td>
                            <td class='result'><?php echo $team_B_result; ?></td>
                            <td><?php echo $spiel_datum; ?></td>
                        </tr>
                    <?php
                    } ?>
                </table>
            </div>
        </div>
    </div>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>