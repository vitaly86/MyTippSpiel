<?php
session_start();
include '../config.php';
if (isset($_SESSION['host_id']) && isset($_SESSION['host_name'])) {
    $db = new Database();
    $conn = $db->connect();
    $host = new Host($conn);
    $event = new Event($conn);
    $user = new User($conn);
    $spiel = new Spiel($conn);
    $host->initHost($_SESSION['host_id']);
    $event->initEvent($_SESSION['host_id']);
    $host_data = $host->getHost();
    $events = $event->getEventsHost();
    $events_ids = $events['event_id'];
?>
    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../CSS/host-homepage.css">
        <?php if (isset($_SESSION['host_id']) && (isset($_SESSION['host_name']))) { ?>
            <script src="../JS/host-homepage.js" defer></script>
        <?php } ?>
        <?php
        if (isset($_GET['error']) or isset($_GET['success'])) { ?>
            <link rel="stylesheet" type="text/css" href="../CSS/host-homepage.css">
        <?php } ?>
        <title><?php echo $host_data['host_fullname']; ?> Home Page</title>
    </head>

    <body>
        <div class="top-bar">
            <h1>Willkommen zum Sport-Event</h1>
            <div id="header"></div>
        </div>
        <div class="container1">
            <a href="einstellungen-host.php">einstellungen</a>
            <a href="../Actions/abmelden_host.php">log out</a>
        </div>
        <h1>Welcome <?php echo $host_data['host_fullname']; ?></h1>
        <hr>
        <div id="content">
            <div class="container2">
                <div class="c2-items">
                    <h1>Aktuelle Events</h1>
                    <ul class='list_events'>
                        <?php for ($i = 0; $i < count($events['event_id']); $i++) {
                            $event_spiele = $events['event_id'][$i]; ?>
                            <li><?php echo $i + 1; ?>.
                                <a href="hinzufuegen_spiele.php?event_id=<?php echo $event_spiele ?>"><?php echo $events['event_name'][$i] ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="c2-items">
                    <img src="../Images/hosts/<?php echo $host_data['host_foto']; ?>" alt="cover-foto">
                </div>
            </div>
            <?php
            for ($event = 0; $event < count($events['event_id']); $event++) {
                $user->get_UserEvent()->initEventUsers($events['event_id'][$event]);
                $ids_usersevents = $user->get_UserEvent()->getUsersId();
            ?>
                <hr id="hr">
                <div class="host-container">
                    <div class="host-events"><?php echo $events['event_name'][$event] ?></div>
                    <?php foreach ($ids_usersevents as $ids_usersevent) {
                        $user->initAllUsersProEvents($ids_usersevent);
                        $user_data = $user->getAllUsers();
                    ?>
                        <table class="users">
                            <tr>
                                <th>User Id</th>
                                <th>User Name</th>
                                <th>User E-Mail</th>
                            </tr>
                            <?php for ($i = 0; $i < count($user_data['users_ids']); $i++) { ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo $user_data['users_names'][$i]; ?></td>
                                    <td><?php echo $user_data['users_emails'][$i]; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
            <?php } ?>
            <hr>
            <div class="container3">
                <div class="c3-items">
                    <h1>ergebnisse eintragen</h1>
                    <ul class='list_events'>
                        <?php
                        $list_events = $spiel->getCountEvents();
                        if (!$list_events) {
                            foreach ($events_ids as $key => $event_id) {
                                $spiel->find_min_SpielDatum($event_id);
                                $start = $spiel->showEventHost();
                                if ($start) {
                        ?>
                                    <li><?php echo $key + 1; ?>.
                                        <a href="register-spiele.php?event_id=<?php echo $event_id ?>"><?php echo $events['event_name'][$key]; ?></a>
                                    </li>
                            <?php }
                            }
                        }
                        $event_existence = $spiel->checkEventsExist();
                        $list_events = $spiel->getCountEvents();
                        if (!$event_existence) { ?>
                            <li>Kein Event angefangen.</li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="footer">&copy; 2024 Tippspiel</div>
    </body>

    </html>

<?php } else {
    $em = "first login";
    Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
} ?>