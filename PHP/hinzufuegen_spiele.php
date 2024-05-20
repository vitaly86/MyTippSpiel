<?php
session_start();
require_once "../config.php";

$fname = $hname = $hemail = "";
if (isset($_GET['event_id'])) {
    $_SESSION['event_id'] = $_GET['event_id'];
    $db = new Database();
    $conn = $db->connect();
    $event = new Event($conn);
    $spiel = new Spiel($conn);
    $event_id = $_GET['event_id'];
    $event->initEventSpiele($event_id);
    $event_data = $event->getEventsHost();
    $event_name = $event_data['event_name'];
    $spiel->initSpieleEventHost($event_id);
    $spiele_data = $spiel->getSpielData();
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/spielen_einfuegen.css">
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/spielen_einfuegen.css">
    <?php } ?>
    <title>Events Spiele</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="homepage-host.php">Startseite</a>
    </div>
    <h1><?php echo $event_name; ?> </h1>
    <h2>Spiele hinzufügen</h2>
    <hr>
    <div class="container">
        <?php
        if (isset($_GET['error'])) { ?>
            <div class="items">
                <div></div>
                <p class="error">
                    <?php echo HostValidation::clean($_GET['error']); ?>
                </p>
            </div>
        <?php  } ?>
        <?php
        if (isset($_GET['success'])) { ?>
            <div class="items">
                <div></div>
                <p class="success">
                    <?php echo HostValidation::clean($_GET['success']); ?>
                </p>
            </div>
        <?php  } ?>
        <form action="../Actions/einfuegen_spielen_daten.php" method="post">
            <div class="items">Spiel Name*<input type="text" name="sname" class="spiel-einfuegen" placeholder="Spiel Name"></div>
            <div class="items">Spiel Datum*<input type="datetime-local" name="sdatum" class="spiel-einfuegen" placeholder="Spiel Datum"></div>
            <input type="submit" name="seinfuegen" id="add_spiel" value="Add Spiel">
        </form>
    </div>
    <hr>
    <div class="container-spiele">
        <div class="spiele-info">
            <h2>Aktuelle Spiele</h2>
            <table>
                <tr>
                    <th>Spiel Id</th>
                    <th>Spiel Name</th>
                    <th>Spiel Datum</th>
                </tr>
                <?php
                foreach ($spiele_data['spiel_id'] as $key => $spiel_info) {
                    $spiel_name = $spiele_data['spiel_name'][$key];
                    $spiel_datum = $spiele_data['spiel_datum'][$key];
                ?>
                    <tr>
                        <td><?php echo $key + 1; ?> </td>
                        <td><?php echo $spiel_name; ?></td>
                        <td><?php echo $spiel_datum; ?></td>
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