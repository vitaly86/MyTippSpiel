<?php
session_start();
include '../config.php';

if (isset($_GET['event_user_id']) && !empty($_GET['event_user_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $user_event = $user->get_UserEvent();
    $current_event = $_GET['event_user_id'];
    $current_user = $_SESSION['user_id'];
    $user_event_data = [$current_event, $current_user];
    $res_user_event = $user_event->insert($user_event_data);
    if ($res_user_event) {
        // echo "Successfully Event registered!";
        $sm = "Successfully Event registered!";
        Util::redirect("homepage-user.php", "success", $sm, $data);
    } else {
        // echo "An error occurred";
        $em = "An error from Events occurred";
        Util::redirect("homepage-user.php", "error", $em, $data);
    }
}
