<?php include 'Controller/connect.php'; ?>
<?php
$db = new Database();
$conn = $db->connect();
$event = new Event($conn);
$spiel = new Spiel($conn);
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if (isset($_GET) && !empty($_GET)) {
        if (isset($_GET['error']) or isset($_GET['success'])) { ?>
            <link rel="stylesheet" type="text/css" href="CSS/homepage.css">
            <script src="JS/homepage.js" defer></script>
        <?php }
    } else { ?>
        <link rel="stylesheet" type="text/css" href="CSS/homepage.css">
        <script src="JS/homepage.js" defer></script>
    <?php } ?>
    <title>Home Page</title>
</head>

<body>
    <div class="top-bar">
        <h1 id="logo">Willkommen zum Sport-Event</h1>
        <div id="header"></div>
    </div>
    <div class="container1">
        <a href="">about us</a>
        <span class="c1-item1">
            <a href="PHP/anmelden-host.php">log in</a>
            <a href="PHP/register-host.php">create host</a>
        </span>
    </div>
    <div id="content">
        <div class="container2">
            <div class="c2-item1">
                <h1>Welcome!</h1>
                <p>This is where our journey begins. Get to know our business and what we do.
                    We're committed to quality and great service.
                    Join us as we grow and succeed together. We're glad you're here to be a part of our story.</p>
                <a href="#" id="sign-up">sign up</a>
                <?php if (isset($_GET) && !empty($_GET) && isset($_GET['user'])) { ?>
                    <div id="user-form-container" style="display: block;">
                    <?php } else { ?>
                        <div id="user-form-container" style="display: none;">
                        <?php } ?>
                        <?php
                        if (!isset($_GET['host'])) {
                            if (isset($_GET['error'])) { ?>
                                <p class="error">
                                    <?php echo htmlspecialchars($_GET['error']); ?>
                                </p>
                            <?php  } ?>
                            <?php
                            if (isset($_GET['success'])) { ?>
                                <p class="success">
                                    <?php echo htmlspecialchars($_GET['success']); ?>
                                </p>
                        <?php  }
                        } else {
                            echo "";
                        } ?>
                        <form action="Actions/anmelden_user_daten.php" method="post">
                            <div class="form-content">Email address*<input type="text" class="user-daten" id="uemail" name="uemail"></div>
                            <div class="form-content">Password*<input type="password" class="user-daten" id="upass" name="upassword"></div>
                            <input type="submit" id="usubmit" value="Sign Up User">
                        </form>
                        </div>
                    </div>
                    <div class="c2-item2">
                        <img src="Images/front-image.jpg" alt="cover-foto">
                    </div>
            </div>
            <table id="table">
                <tr>
                    <th>Id</th>
                    <th>Anfang Datum</th>
                    <th id='limit'>Event</th>
                </tr>
                <?php
                $event->startPageEvents();
                $start_events = $event->getEventsStart();
                $count = 0;
                for ($i = 0; $i < count($start_events['event_id']); $i++) {
                    $user_event_id = $start_events['event_id'][$i];
                    $user_event_name = $start_events['event_name'][$i];
                    $spiel_exist = $spiel->verifySpieleEvent($user_event_id);
                    if ($spiel_exist) {
                        $count += 1;
                        $spiel->find_min_SpielDatum($user_event_id);
                        $max_enroll_datum = $spiel->getEventStart();
                        $event_ok = $spiel->get_Tipps_Available($max_enroll_datum);
                        if ($event_ok) { ?>
                            <tr>
                                <td class='id'><?php echo $count; ?></td>
                                <td class='datum'><?php echo $max_enroll_datum; ?></td>
                                <td class='event'><?php echo $user_event_name; ?></td>
                                <td class='chose'><a href="PHP/register-user.php?user_event_id=<?php echo $user_event_id; ?>" class='enroll'>Enroll</a></td>
                            </tr>
                        <?php  } else { ?>
                            <tr>
                                <td class='id'><?php echo $count; ?></td>
                                <td class='datum'><?php echo $max_enroll_datum; ?></td>
                                <td class='event'><?php echo $user_event_name; ?></td>
                                <td class='chose'><a href="#" class='abgelaufen'>Abgelaufen</a></td>
                            </tr>
                        <?php } ?>
                <?php }
                } ?>
            </table>
            <div id="sep"></div>
        </div>
        <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>