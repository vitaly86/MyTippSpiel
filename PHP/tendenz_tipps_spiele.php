<?php
session_start();
require_once "../config.php";

if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $host = new Host($conn);
    $event = new Event($conn);
    $spiel = new Spiel($conn);
    $id_event = $_SESSION['event_id'];
    $id_spiel = $_GET['spiel_id'];
    $host->initHost($id_event);
    $event->initEventSpiele($id_event);
    $spiel->initSpieleEvent($id_event);
    $event_data = $event->getEventSpiele();
    $id_host = $event_data['event_host'];
    $host->initHost($id_host);
    $host_data = $host->getHost();
    $spiele_data = $spiel->getSpielData();
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
    <h1>Tendenz Tipps</h1>
    <hr>
    <div class="container">
        <div class="event_info">
            <div class="details"><?php echo $host_data['host_fullname']; ?></div>
            <div>
                <hr>
            </div>
            <div class="details"><?php echo $event_data['event_name']; ?></div>
            <div>
                <hr>
            </div>
        </div>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>