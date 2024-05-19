<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = UserValidation::clean($_POST['uname']);
    $user_email = UserValidation::clean($_POST['uemail']);
    $user_pass = UserValidation::clean($_POST['upass']);

    $data = "uname=" . $user_name . "&hemail=" . $user_email . "&user_event_id=" . $_SESSION['user_event'];

    if (!UserValidation::userName($user_name)) {
        $em = "Your Name is Invalid";
        Util::redirect("../PHP/register-user.php", "error", $em, $data);
    } else if (!UserValidation::userEmail($user_email)) {
        $em = "Invalid User Email";
        Util::redirect("../PHP/register-user.php", "error", $em, $data);
    } else if (!UserValidation::userPassword($user_pass)) {
        $em = "Invalid User Passwort";
        Util::redirect("../PHP/register-user.php", "error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $user = new User($conn);
        if ($user->exists()) {
            if ($user->is_UserUnique($user_email)) {
                # password hash
                # Test@pass1
                $user_password = password_hash($user_pass, PASSWORD_DEFAULT);
                $user_data = [$user_name, $user_email, $user_password];
                $res = $user->insert($user_data);
                if ($res) {
                    $user->get_CurrentUser($user_email);
                    $current_user = $user->getUser()['user_id'];
                    $current_event = $_SESSION['user_event'];
                    $user_event = $user->get_UserEvent();
                    $user_event_data = [$current_event, $current_user];
                    $res_user_event = $user_event->insert($user_event_data);
                    if ($res_user_event) {
                        // echo "Successfully Event registered!";
                        $sm = "Successfully Event registered!";
                        // Util::redirect("../PHP/register-user.php", "success", $sm, $data);
                    } else {
                        // echo "An error occurred";
                        $em = "An error from Events occurred";
                        Util::redirect("../PHP/register-user.php", "error", $em, $data);
                    }
                    $user_host = $user->get_UserHost();
                    $event_table = $user_event->connection_to_Event();
                    $get_host = $event_table->retrieveHost($current_event);
                    $current_host = $event_table->retainHost();
                    $user_host_data = [$current_host, $current_user];
                    $res_user_host = $user_host->insert($user_host_data);
                    if ($res_user_host) {
                        // echo "Successfully Host registered!";
                        $sm = "Successfully Host registered!";
                        // Util::redirect("../PHP/register-user.php", "success", $sm, $data);
                    } else {
                        // echo "An error occurred";
                        $em = "An error from Hosts occurred";
                        Util::redirect("../PHP/register-user.php", "error", $em, $data);
                    }
                    $sm = "Successfully registered!";
                    Util::redirect("../PHP/register-user.php", "success", $sm, $data);
                } else {
                    $em = "An error from Users occurred";
                    Util::redirect("../PHP/register-user.php", "error", $em, $data);
                }
            } else {
                $em = "The Email ($user_email) is already taken";
                Util::redirect("../PHP/register-user.php", "error", $em, $data);
            }
        } else {
            $user_password = password_hash($user_pass, PASSWORD_DEFAULT);
            $user_data = [$user_name, $user_email, $user_password];
            $res = $user->insert($user_data);

            if ($res) {
                echo "The User is inserted <br>";
                $user->get_CurrentUser($user_email);
                $current_user = $user->getUser()['user_id'];
                $current_event = $_SESSION['user_event'];
                $user_event = $user->get_UserEvent();
                $user_event_data = [$current_event, $current_user];
                $res_user_event = $user_event->insert($user_event_data);
                if ($res_user_event) {
                    // echo "Successfully Event registered!";
                    $sm = "Successfully Event registered!";
                    // Util::redirect("../PHP/register-user.php", "success", $sm, $data);
                } else {
                    // echo "An error occurred";
                    $em = "An error occurred";
                    Util::redirect("../PHP/register-user.php", "error", $em, $data);
                }
                $user_host = $user->get_UserHost();
                $event_table = $user_event->connection_to_Event();
                $get_host = $event_table->retrieveHost($current_event);
                $current_host = $event_table->retainHost();
                $user_host_data = [$current_host, $current_user];
                $res_user_host = $user_host->insert($user_host_data);
                if ($res_user_host) {
                    // echo "Successfully Host registered!";
                    $sm = "Successfully Host registered!";
                    // Util::redirect("../PHP/register-user.php", "success", $sm, $data);
                } else {
                    // echo "An error occurred";
                    $em = "An error occurred";
                    Util::redirect("../PHP/register-user.php", "error", $em, $data);
                }
            } else {
                $em = "An error occurred";
                Util::redirect("../PHP/register-user.php", "error", $em, $data);
            }
            $sm = "Successfully registered!";
            Util::redirect("../PHP/register-user.php", "success", $sm, $data);
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/register-user.php", "error", $em);
}
