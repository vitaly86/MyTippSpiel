<?php
require_once "../config.php";

$fname = $hname = $hemail = "";
if (isset($_GET['fname'])) {
    $fname = $_GET['fname'];
}

if (isset($_GET['hname'])) {
    $hname = $_GET['hname'];
}

if (isset($_GET['hemail'])) {
    $hemail = $_GET['hemail'];
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/host-anmelden.css">
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/host-register.css">
    <?php } ?>
    <title>Anmelden Host</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <h1>Anmelden</h1>
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
        <form action="../Actions/anmelden_host_daten.php" method="post">
            <div class="items">Host Name*<input type="text" name="hname" class="host-anmelden" placeholder="Host Name" value=<?php echo $hname; ?>></div>
            <div class="items">Password*<input type="password" name="hpass" class="host-anmelden" placeholder="Host Password" value=<?php echo $hemail; ?>></div>
            <div class="items">Create Event*<input type="text" name="hevent" class="host-anmelden" placeholder="Your Event"></div>
            <!-- <div class="items">New Event*
                <div class="options">
                    <input type="radio" name="ja-event" value="Ja">Ja
                    <input type="radio" name="nein-event" value="Nein">Nein
                </div>
            </div>
            <div class="items">Create Event*<input type="text" name="hevent" class="host-anmelden" required></div> -->
            <input type="submit" name="hanmelden" id="h-anmelden" value="Anmelden">
            <div><a href="register-host.php">Register</a></div>
        </form>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>