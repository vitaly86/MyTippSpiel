<?php
session_start();
require_once "../config.php";
$db = new Database();
$conn = $db->connect();
$host = new Host($conn);
$host_id = $_SESSION['host_id'];
$host->initHostUser($host_id);
$host_data = $host->getHostUser();
$host_name = $host_data['host_fullname'];
$tordiff = $host_data['host_tordiff'];
$winnloss = $host_data['host_winnloss'];
$unentschieden = $host_data['host_equality'];

?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/host-einstellungen.css">
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/host-einstellungen.css">
    <?php } ?>
    <title>Host Einstellungen</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="homepage-host.php">Startseite</a>
    </div>
    <h1><?php echo $host_name; ?></h1>
    <h1>Einstellungen</h1>
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
        <form action="../Actions/einstellungen_host_daten.php" method="post">
            <h2>Punkte für genauer Tipp</h2>
            <div class="items">Richtige Tordifferenz *<input type="text" name="tordiff" class="tipps-values" placeholder="Tordifferenz"><?php echo $tordiff; ?>p</div>
            <hr>
            <h2>Punkte für Tendenz Tipp</h2>
            <div class="items">Übereinstimmen (Sieg / Niederlage) *<input type="text" name="stimmt" class="tipps-values" placeholder="Sieg / Niederlage"><?php echo $winnloss; ?>p</div>
            <div class="items">Unentschieden *<input type="text" name="gleich" class="tipps-values" placeholder="Unentschieden"><?php echo $unentschieden; ?>p</div>
            <input type="submit" name="hconfig" id="host_config" value="Speichern">
        </form>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>