<?php
session_start();
require_once "../config.php";

if (isset($_GET['event_id']) && isset($_GET['spiel_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $spiel = new Spiel($conn);

    $event_id = $_SESSION['event_id'];
    $_SESSION['spiel_id'] = $_GET['spiel_id'];
    $spiel_id = $_GET['spiel_id'];

    $spiel->init_one_Spiel($spiel_id);
    $spiel_data = $spiel->get_one_SpielData();
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/ergebnisse_einfuegen.css">
    <?php if (isset($_SESSION['host_id']) && (isset($_SESSION['host_name'])) && isset($_GET['event_id']) && isset($_GET['spiel_id'])) { ?>
        <script src="../JS/ergebnisse_einfuegen.js" defer></script>
    <?php } ?>
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/ergebnisse_einfuegen.css">
    <?php } ?>
    <title>Events Spiele</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="register-spiele.php?event_id=<?php echo $event_id; ?>&spiel_id=<?php echo $spiel_id; ?>">select spiel</a>
    </div>
    <h2><?php echo $spiel_data['spiel_name']; ?></h2>
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
        <form action="../Actions/ergebnisse_spielen_daten.php" method="post">
            <div class="items">Team A*<input type="text" name="team_A_result" class="ergebniss-einfuegen" placeholder="Team A Ergebnis"></div>
            <div class="items">Team B*<input type="text" name="team_B_result" class="ergebniss-einfuegen" placeholder="Team B Ergebnis"></div>
            <input type="submit" name="er_einfuegen" id="add_ergebniss" value="Submit Result">
        </form>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>