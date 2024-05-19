<?php
session_start();
include '../config.php';

if (isset($_GET['event_user_id']) && !empty($_GET['event_user_id'])) {
    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $event = new Event($conn);
    $user_event = $user->get_UserEvent();
    $user_host = $user->get_UserHost();
    $current_event = $_GET['event_user_id'];
    $current_user = $_SESSION['user_id'];
    $event->retrieveHost($current_event);
    $current_host = $event->retainHost();
    $user_event_data = [$current_event, $current_user];
    $res_user_event = $user_event->insert($user_event_data);
    $user_host_data = [$current_host, $current_user];
    $res_user_host = $user_host->insert($user_host_data);
    if ($res_user_event && $res_user_host) {
        // echo "Successfully Event registered!";
        $sm = "Successfully Event registered!";
        Util::redirect("homepage-user.php", "success", $sm, $data);
    } else {
        $em = "The Event could not be registered";
        Util::redirect("homepage-user.php", "error", $em, $data);
    }
}
