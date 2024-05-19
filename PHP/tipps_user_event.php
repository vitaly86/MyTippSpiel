<?php
session_start();
require_once "../config.php";
if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $event = new Event($conn);
    $spiel = new Spiel($conn);
    $tippgenau = new TippGenau($conn);
    $tipptendenz = new TippTendenz($conn);
    $user_id = $_SESSION['user_id'];
    $event_id = $_GET['event_id'];
    $event->initEventSpiele($event_id);
    $event_data = $event->getEventSpiele();
    $event_name = $event_data['event_name'];
    $spiel->initSpieleEvent($event_id);
    $curr_event_spiele = $spiel->getSpielData();
    $spiele_ids = $curr_event_spiele['spiel_id'];
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/user_event_tipps.css">
    <title>User Tipps</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="homepage-user.php">Startseite</a>
    </div>
    <h1><?php echo $event_name; ?> </h1>
    <hr>
    <div id="content">
        <div class="tipps-container">
            <div class='tipps-menu'>
                <div class="config">
                    <ul>
                        <li><a href="?event_id=<?php echo $event_id; ?>&tipp=genau">Genau</a></li>
                        <li><a href="?event_id=<?php echo $event_id; ?>&tipp=tendenz">Tendenz</a></li>
                    </ul>
                </div>
            </div>
            <div class="tipps-info">
                <?php
                if (isset($_GET['tipp'])) {
                    if ($_GET['tipp'] == 'genau') { ?>
                        <table>
                            <tr>
                                <th>Spiel</th>
                                <th>Tordifferenz</th>
                                <th>Datum</th>
                            </tr>
                            <?php foreach ($spiele_ids as $key => $spiel_id) {
                                $curr_data = [$spiel_id, $user_id];
                                $tippgenau->initGenauTipp($curr_data);
                                $tippgenau_data = $tippgenau->getTippGenauAll();
                                $spiel_name = $curr_event_spiele['spiel_name'][$key];
                                $tordiff = $tippgenau_data['tordiff'];
                                $genau_datum = $tippgenau_data['datum'];
                            ?>
                                <tr>
                                    <td> <?php echo $spiel_name; ?> </td>
                                    <td>
                                        <div class="tipp"><?php echo $tordiff; ?></div>
                                    </td>
                                    <td>
                                        <div class="tipp"><?php echo $genau_datum; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else if ($_GET['tipp'] == 'tendenz') { ?>
                        <table>
                            <tr>
                                <th>Spiel</th>
                                <th>Team A</th>
                                <th>Team B</th>
                                <th>Datum</th>
                            </tr>
                            <?php foreach ($spiele_ids as $key => $spiel_id) {
                                $curr_data = [$spiel_id, $user_id];
                                $tipptendenz->initTendenzTipp($curr_data);
                                $tipptendenz_data = $tipptendenz->getTippTendenzAll();
                                $spiel_name = $curr_event_spiele['spiel_name'][$key];
                                $teamAscore = $tipptendenz_data['tipp_teamA'];
                                $teamBscore = $tipptendenz_data['tipp_teamB'];
                                $tendenz_datum = $tipptendenz_data['datum'];
                            ?>
                                <tr>
                                    <td><?php echo $spiel_name; ?></td>
                                    <td>
                                        <div class="tipp"><?php echo $teamAscore; ?></div>
                                    </td>
                                    <td>
                                        <div class="tipp"><?php echo $teamBscore; ?></div>
                                    </td>
                                    <td>
                                        <div class="tipp"><?php echo $tendenz_datum; ?></div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                <?php }
                } else {
                    echo "<h2>Aktuelle Tipps</h2>";
                } ?>
            </div>
        </div>
    </div>

    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>