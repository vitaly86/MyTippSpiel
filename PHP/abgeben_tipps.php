<?php
session_start();
require_once "../config.php";
if (isset($_GET['spiel_id']) && !empty($_GET['spiel_id'])) {
    $_SESSION['spiel_id'] = $_GET['spiel_id'];
?>

    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../CSS/tipps-abgeben.css">
        <?php
        if (isset($_GET['error']) or isset($_GET['success'])) { ?>
            <link rel="stylesheet" type="text/css" href="../CSS/tipps-abgeben.css">
        <?php } ?>
        <title>User Tipps Daten</title>
    </head>

    <body>
        <h1>Willkommen zum Sport-Event</h1>
        <div id="header"></div>
        <div class="entry">
            <a href="homepage-user.php">Startseite</a>
            <a href="tipps-user.php">Deine Tipps</a>
        </div>
        <h1>Geben Sie bitte den Spiel Tipp ab</h1>
        <hr>
        <div class="container">
            <?php
            if (isset($_GET['t_genau_error'])) { ?>
                <div class="items">
                    <div></div>
                    <p class="error">
                        <?php echo TippValidation::clean($_GET['t_genau_error']); ?>
                    </p>
                </div>
            <?php  } ?>
            <?php
            if (isset($_GET['t_genau_success'])) { ?>
                <div class="items">
                    <div></div>
                    <p class="success">
                        <?php echo TippValidation::clean($_GET['t_genau_success']); ?>
                    </p>
                </div>
            <?php } ?>
            <form action="../Actions/user_tippsgenau_daten.php" method="post">
                <h2>Genauer Tipp</h2>
                <div class="items">Tordifferenz *<input type="text" name="tordiff" class="tipps-values" placeholder="Score"></div>
                <div class="items">Action *
                    <div class="actions">
                        <input type="radio" id="insert_tipp" class="action" name="op_insert">Insert
                        <input type="radio" id="update_tipp" class="action" name="op_update">Update
                    </div>
                </div>
                <div class="items">Datum *<input type="datetime-local" name="t_datum" class="tipps-values" placeholder="Your current Date"></div>
                <input type="submit" name="t_genau" id="genauer_tipp" value="Tipp abgeben">
            </form>
            <hr>
            <?php
            if (isset($_GET['t_tendenz_error'])) { ?>
                <div class="items">
                    <div></div>
                    <p class="error">
                        <?php echo TippValidation::clean($_GET['t_tendenz_error']); ?>
                    </p>
                </div>
            <?php
            } ?>
            <?php
            if (isset($_GET['t_tendenz_success'])) { ?>
                <div class="items">
                    <div></div>
                    <p class="success">
                        <?php echo TippValidation::clean($_GET['t_tendenz_success']); ?>
                    </p>
                </div>
            <?php
            } ?>
            <form action="../Actions/user_tippstendenz_daten.php" method="post">
                <h2>Tendenz Tipp</h2>
                <div class="items">Teams *
                    <div class="teams">
                        <input type="checkbox" id="team_A" class="team" name="op_teamA">Team A
                        <input type="checkbox" id="team_B" class="team" name="op_teamB">Team B
                    </div>
                </div>
                <div class="items">Tipp *
                    <div class="tendenz_tipps">
                        <input type="radio" id="sieg" class="tendenz_tipp" name="t_sieg">Sieg
                        <input type="radio" id="niederlage" class="tendenz_tipp" name="t_niederlage">Niederlage
                        <input type="radio" id="unentschieden" class="tendenz_tipp" name="t_unentschieden">Unentschieden
                    </div>
                </div>
                <div class="items">Action *
                    <div class="actions">
                        <input type="radio" id="insert_tipp" class="action" name="op_insert">Insert
                        <input type="radio" id="update_tipp" class="action" name="op_update">Update
                    </div>
                </div>
                <div class="items">Datum *<input type="datetime-local" name="t_datum" class="tipps-values" placeholder="Your current Date"></div>
                <input type="submit" name="t_tendenz" id="tendenz_tipp" value="Tipp abgeben">
            </form>
        </div>
        </div>
        <hr>
        <div id="footer">&copy; 2024 Tippspiel</div>
    </body>

    </html>
<?php } else {
    $em = "first select a spiel";
    Util::redirect("../PHP/homepage-user.php", "error", $em, $data);
} ?>