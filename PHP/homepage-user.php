<?php
session_start();
include '../config.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $event = new Event($conn);
    $spiele = new Spiel($conn);
    $user->initUser($_SESSION['user_id']);
    $user_data = $user->getUser();
    $user->get_UserEvent()->initUserEvent($_SESSION['user_id']);
    $event_ids = $user->get_UserEvent()->getEventId();
    $event->initEventUser($event_ids);
    $events_data = $event->getUserEvents();
    $current_year = '2024';
    $current_month = '01';
    $current_day = '25';
    $current_date = "$current_year-$current_month-$current_day";
    $spiele_tipp_data = array(
        'year' => $current_year,
        'month' => $current_month,
        'day' => $current_day
    );
?>

    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../CSS/user-homepage.css">
        <?php
        if (isset($_GET['error']) or isset($_GET['success'])) { ?>
            <link rel="stylesheet" type="text/css" href="../CSS/user-homepage.css">
        <?php } ?>
        <title>User Home Page</title>
    </head>

    <body>
        <h1>Willkommen zum Sport-Event</h1>
        <div id="header"></div>
        <div class="container1">
            <a href="PHP/register-host.php">hosts</a>
            <a href="../Actions/abmelden-host.php">log out</a>
        </div>
        <h1>Welcome <?php echo $user_data['user_name']; ?></h1>
        <h1>Deine aktuelle Events</h1>
        <hr>
        <div id="content">
            <div class="container2">
                <div class="c2-items">
                    <?php for ($ev = 0; $ev < count($events_data['event_id']); $ev++) {  ?>
                        <h1 class="events"><?php echo $events_data['event_name'][$ev] ?></h1>
                        <hr>
                        <?php
                        $curr_event_id = $events_data['event_id'][$ev];
                        $current_data = [$curr_event_id, $current_date];
                        $tipp_spiele = $spiele->initSpieleExists($current_data);
                        if ($tipp_spiele) {
                            $spiele->initSpieleEvent($curr_event_id);
                            $spiele_data = $spiele->getSpielData();
                        ?>
                            <h1 class="status">Status: Laufen</h1>
                            <hr>
                            <h1 class="spiele">Spiele</h1>
                            <hr>
                            <div class="contents_spiel">
                                <div>
                                    <?php
                                    for ($i = 0; $i < count($spiele_data['spiel_name']); $i++) {
                                        $spiele->initSpieleLimit($spiele_data['spiel_datum'][$i]);
                                        $tipp_ok = $spiele->getTippsAvailability($spiele_tipp_data);
                                        if ($tipp_ok) {
                                    ?>
                                            <div class="spiel_data">Tippen:
                                                <a class="spiele" href="abgeben_tipps.php?spiel_id=<?php echo $spiele_data['spiel_id'][$i]; ?>">
                                                    <?php echo $spiele_data['spiel_name'][$i]; ?>
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="spiel_data">Tippen:
                                                <?php echo $spiele_data['spiel_name'][$i]; ?>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                                <div class="spiel_data">
                                    <?php
                                    for ($i = 0; $i < count($spiele_data['spiel_datum']); $i++) {
                                        $spiele->initSpieleLimit($spiele_data['spiel_datum'][$i]);
                                        $tipp_ok = $spiele->getTippsAvailability($spiele_tipp_data);
                                        if ($tipp_ok) {
                                    ?>
                                            <div class="spiel_data">Beginnt am:
                                                <span class="spiele"><?php echo $spiele_data['spiel_datum'][$i]; ?> </span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="spiel_data"><span class="closed">Nicht verfügbar</span></div>
                                    <?php
                                        }
                                    } ?>
                                </div>
                            </div>
                            <hr>
                        <?php } else { ?>
                            <h1 class="status">Status: Abgelaufen</h1>
                            <hr>
                            <h1 class="status"><a href="ergebnisse_event.php?event_id=<?php echo $curr_event_id; ?>">Ergebnisse</a></h1>
                            <hr>
                    <?php
                        }
                    } ?>
                    <div id="enquire">Möchtest du bei anderen Events teilnehmen?</div>
                    <div class="chose">
                        <a href="#">Ja</a>
                        <a href="#">Nein</a>
                    </div>
                    <hr>
                </div>

            </div>
            <table id='table'>
                <tr>
                    <th>Id</th>
                    <th id='limit'>Event</th>
                </tr>
                <?php
                $user1 = new User($conn);
                $event1 = new Event($conn);
                $user_id = $_SESSION['user_id'];
                $data_event_ids = $event_ids['user_event'];
                $user1->get_UserEvent()->nullUserEvent($data_event_ids);
                $null_event_ids = $user1->get_UserEvent()->getEventId()['user_event'];
                $event1->nullEventUser($null_event_ids);
                $null_events_data = $event1->getUserEvents();
                for ($i = 0; $i < count($null_events_data['event_id']); $i++) {
                    $null_evt_id = $null_events_data['event_id'][$i];
                    $null_evt_name = $null_events_data['event_name'][$i];
                ?>
                    <tr>
                        <td class='id'><?php echo $null_evt_id; ?></td>
                        <td class='event'><?php echo $null_evt_name; ?></td>
                        <td class="chose"><a href="new_event_user.php?event_user_id=<?php echo $null_evt_id; ?>" class="enroll">Enroll</a></td>
                    </tr>
                <?php }
                ?>

            </table>
        </div>
        <div id="footer">&copy; 2024 Tippspiel</div>
    </body>

    </html>

<?php } else {
    $em = "first login";
    Util::redirect("../index.php", "error", $em, $data);
} ?>