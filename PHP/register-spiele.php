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
    $spiel->initSpieleEvent($id_event);
    $event_data = $event->getEventSpiele();
    $spiele_data = $spiel->getSpielData();
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
    <h1><?php echo $event_data['event_name']; ?></h1>
    <h1>Gespielte Spiele</h1>
    <hr>
    <div id="container">
        <ul class='list_spiele'>
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
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>