<?php
session_start();
include "rangliste_rechnungen.php";

if (isset($_GET['event_id']) && !empty($_GET['event_id'])) {
    $result = new Result($conn);
    $result->initOrderResults($id_event);
    $ordered_data = $result->getOrderResults();
    for ($i = 0; $i < count($ordered_data['id_users']); $i++) {
        $id_ev_user = $ordered_data['id_users'][$i];
        $ev_user_score = $ordered_data['user_score'][$i];
        $user->initUser($id_ev_user);
        $ev_user_data = $user->getUser();
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../CSS/users-rangliste.css">
    <title>Rangliste Users</title>
</head>

<body>
    <h1>Willkommen zum Sport-Event</h1>
    <div id="header"></div>
    <div class="entry">
        <a href="ergebnisse_event.php?event_id=<?php echo $id_event; ?>">Eventseite</a>
    </div>
    <h1>Rangliste</h1>
    <hr>
    <div class="container">
        <div class="event_info">
            <table class="rangliste">
                <tr>
                    <th>Platz</th>
                    <th>User Name</th>
                    <th>Punkten</th>
                </tr>
                <?php for ($i = 0; $i < count($ordered_data['id_users']); $i++) {
                    $id_ev_user = $ordered_data['id_users'][$i];
                    $ev_user_score = $ordered_data['user_score'][$i];
                    $user->initUser($id_ev_user);
                    $ev_user_data = $user->getUser();
                ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo $ev_user_data['user_name']; ?></td>
                        <td><?php echo $ev_user_score; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
    <hr>
    <div id="footer">&copy; 2024 Tippspiel</div>
</body>

</html>