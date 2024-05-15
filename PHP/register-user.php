<?php
session_start();
include "../config.php";
$_SESSION['user_event'] = $_GET['event_user_id'];
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/user-register.css">
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/user-register.css">
    <?php } ?>
    <title>Register Host</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <h1>Register User</h1>
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
        <form action="../Actions/register_user_daten.php" method="POST">
            <div class="items">User Name*<input type="text" name="uname" class="user-register" placeholder="User Name"></div>
            <div class="items">Email address*<input type="text" name="uemail" class="user-register" placeholder="User Email"></div>
            <div class="items">Password*<input type="password" name="upass" class="user-register" placeholder="User Password"></div>
            <input type="submit" name="uregister" id="u-register" value="Register">
            <hr>
            <div id="request"><span>Haben Sie schon ein Konto bei Sport-Event ?</span><a href="../index.php">Sign Up</a></div>
        </form>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>