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
    <link rel="stylesheet" type="text/css" href="../CSS/host-register.css">
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/host-register.css">
    <?php } ?>
    <title>Register Host</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <h1>Register</h1>
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
        <form action="../Actions/register_host_daten.php" method="POST">
            <div class="items">Full Name*<input type="text" name="hfullname" class="host-register" placeholder="Your Full Name" value=<?php echo $fname; ?>></div>
            <div class="items">Host Name*<input type="text" name="hname" class="host-register" placeholder="Host Name" value=<?php echo $hname; ?>></div>
            <div class="items">Email address*<input type="text" name="hemail" class="host-register" placeholder="Host Email" value=<?php echo $hemail; ?>></div>
            <div class="items">Password*<input type="password" name="hpass" class="host-register" placeholder="Host Password"></div>
            <div class="items">Upload Foto*<input type="file" name="hfoto" class="host-register"></div>
            <input type="submit" name="hregister" id="h-register" value="Register">
            <div><a href="anmelden-host.php">Anmelden</a></div>
        </form>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>