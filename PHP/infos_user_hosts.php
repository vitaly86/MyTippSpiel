<?php
session_start();
include '../config.php';
if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $host = new Host($conn);
    $event = new Event($conn);
    $user_id = $_SESSION['user_id'];
    $user_hosts = $user->get_UserHost();
    $user_hosts->initUserHost($user_id);
    $host_ids = $user_hosts->getHostId();
    $user_events = $user->get_UserEvent();
    $user_events->initUserEvent($user_id);
    $event_ids = $user_events->getEventId()['user_event'];
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/user_hosts_infos.css">
    <?php
    if (isset($_GET['error']) or isset($_GET['success'])) { ?>
        <link rel="stylesheet" type="text/css" href="../CSS/user_hosts_infos.css">
    <?php } ?>
    <title>User Hosts Infos</title>
</head>

<body>
    <div class="top-bar">
        <h1>Willkommen zum Sport-Event</h1>
        <div id="header"></div>
    </div>
    <div class="container">
        <a href="homepage-user.php">startseite</a>
    </div>
    <h1>deine hosts infos</h1>
    <hr>
    <div id="content">
        <div class="host-container">
            <?php foreach ($host_ids['user_host'] as $key => $host_id) {
                $host->initHostUser($host_id);
                $host_data = $host->getHostUser(); ?>
                <div class='host-menu'>
                    <div class="config">
                        <ul>
                            <li><a href="?host_id=<?php echo $host_data["host_id"]; ?>"><?php echo $key + 1 . '. ' . $host_data['host_fullname']; ?></a></li>
                        </ul>
                    </div>
                </div>
            <?php } ?>
            <?php foreach ($host_ids['user_host'] as $host_id) {
                $host->initHostUser($host_id);
                $host_data = $host->getHostUser();
                $av_events = new Event($conn);
                $all_users = new User($conn);
                $av_events->initEventHost($host_id, $event_ids);
                $events_data = $av_events->getHostEvents();
                if (isset($_GET['host_id'])) {
                    if ($host_id == $_GET['host_id']) {
            ?>
                        <div class="host-info">
                            <div class="h-items">
                                <h1><?php echo $host_data['host_fullname']; ?> </h1>
                                <h2>E-Mail: <?php echo $host_data['host_email']; ?> </h2>
                                <hr>
                                <h2>Regeln</h2>
                                <ul class='regeln'>
                                    <li>Richtige Tordifferenz: <?php echo $host_data['host_tordiff']; ?>p</li>
                                    <li>Übereinstimmen (Sieg / Niederlage): <?php echo $host_data['host_winnloss']; ?>p</li>
                                    <li>Unentschieden: <?php echo $host_data['host_equality']; ?>p</li>
                                    <li>Versagen: 0p</li>
                                </ul>
                            </div>
                            <div class="h-items">
                                <img src="../Images/hosts/<?php echo $host_data['host_foto']; ?>" alt="cover-foto">
                            </div>
                        </div>
                        <?php
                        for ($event = 0; $event < count($events_data['event_id']); $event++) {
                            $all_users->get_UserEvent()->initEventUsers($events_data['event_id'][$event]);
                            $ids_usersevents = $all_users->get_UserEvent()->getUsersId();
                        ?>
                            <div class="host-events-info">
                                <hr class="sep">
                                <div class="host-events"><?php echo $events_data['event_name'][$event] ?></div>
                                <?php foreach ($ids_usersevents as $ids_usersevent) {
                                    $all_users->initAllUsersProEvents($ids_usersevent);
                                    $user_data = $all_users->getAllUsers();
                                    $zahl_users = count($user_data['users_ids']);
                                ?>
                                    <table class="users">
                                        <tr>
                                            <th>User Id</th>
                                            <th>User Name</th>
                                            <th>User E-Mail</th>
                                        </tr>
                                        <?php for ($i = 0; $i < count($user_data['users_ids']); $i++) { ?>
                                            <tr>
                                                <td><?php echo $user_data['users_ids'][$i]; ?></td>
                                                <td><?php echo $user_data['users_names'][$i]; ?></td>
                                                <td><?php echo $user_data['users_emails'][$i]; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                    <div class='zahl'>Zahl Teilnehmern: <?php echo $zahl_users; ?> </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <hr class="sep-base">
            <?php }
                } else {
                    echo "";
                }
            } ?>
        </div>
        <hr class="sep">
    </div>
    <div class="footer">&copy; 2024 Tippspiel</div>
</body>

</html>