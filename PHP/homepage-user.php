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
        <div class="top-bar">
            <h1>Willkommen zum Sport-Event</h1>
            <div id="header"></div>
        </div>
        <div class="container1">
            <a href="infos_user_hosts.php">hosts</a>
            <a href="../Actions/abmelden.php">log out</a>
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
                        $tipp_spiele = $spiele->initSpieleExists($curr_event_id);
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
                                    for ($i = 0; $i < count($spiele_data['spiel_id']); $i++) {
                                        $spiel_datum = $spiele_data['spiel_datum'][$i];
                                        $tipp_ok = $spiele->getTippsAvailability($spiel_datum);
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
                                    for ($i = 0; $i < count($spiele_data['spiel_id']); $i++) {
                                        $spiel_datum = $spiele_data['spiel_datum'][$i];
                                        $tipp_ok = $spiele->getTippsAvailability($spiel_datum);
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
                            <h1 class="tipps"><a href="tipps_user_event.php?event_id=<?php echo $curr_event_id; ?>">Deine Current Tipps</a></h1>
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
                    <th>Beginn</th>
                    <th>Ende</th>
                    <th id='limit'>Event</th>
                </tr>
                <?php
                $user1 = new User($conn);
                $event1 = new Event($conn);
                $spiel1 = new Spiel($conn);
                $user_id = $_SESSION['user_id'];
                $data_event_ids = $event_ids['user_event'];
                $event1->find_null_UserEvents($data_event_ids);
                $null_event_user_ids = $event1->get_null_UserEvents();
                $count = 0;
                foreach ($null_event_user_ids as $key => $null_event_id) {
                    $spiel_exist = $spiel1->verifySpieleEvent($null_event_id);
                    if ($spiel_exist) {
                        $count += 1;
                        $event1->init_null_EventsUser($null_event_id);
                        $null_event_data = $event1->null_data_UserEvents();
                        $curr_null_event_id = $null_event_data['event_id'];
                        $curr_null_event_name = $null_event_data['event_name'];
                        $spiel1->find_min_SpielDatum($curr_null_event_id);
                        $spiel1->find_max_SpielDatum($curr_null_event_id);
                        $event_begin = $spiel1->getEventStart();
                        $event_ended = $spiel1->getEventEnde();
                        $get_momentum = $spiel1->get_zeitraum_Event($event_begin, $event_ended);
                ?>
                        <tr>
                            <td class='id'><?php echo $count; ?></td>
                            <td class='begin'><?php echo $event_begin; ?></td>
                            <td class='end'><?php echo $event_ended; ?></td>
                            <td class='event'><?php echo $curr_null_event_name; ?></td>
                            <?php if ($get_momentum == "enroll") { ?>
                                <td class="chose"><a href="new_event_user.php?event_user_id=<?php echo $null_event_id; ?>" class="option">Enroll</a></td>
                            <?php
                            } else if ($get_momentum == "innerhalb") { ?>
                                <td class="chose"><a href="ergebnisse_event.php?event_id=<?php echo $null_event_id; ?>&zeitraum=innerhalb" class="option">Innerhalb</a></td>
                            <?php
                            } else if ($get_momentum == "ergebnisse") { ?>
                                <td class="chose"><a href="ergebnisse_event.php?event_id=<?php echo $null_event_id; ?>" class="option">Ergebnisse</a></td>
                            <?php } ?>
                        </tr>
                <?php }
                }

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