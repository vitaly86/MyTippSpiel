<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // assumed that we have two options: 
    //1. with Event included && 2. without Event
    if (isset($_POST['hevent']) && !empty($_POST['hevent'])) {
        $host_name = HostValidation::clean($_POST['hname']);
        $host_pass = HostValidation::clean($_POST['hpass']);
        $host_event = EventValidation::clean($_POST['hevent']);

        // $data = "fname=" . $host_full_name . "&hname=" . $host_name . "&hemail=" . $host_email;

        if (!HostValidation::hostName($host_name)) {
            $em = "Invalid Host Name";
            Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
        } else if (!HostValidation::hostPassword($host_pass)) {
            $em = "Invalid Host Passwort";
            Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
        } else if (!EventValidation::validate($host_event)) {
            $em = "Invalid Host Event Name";
            Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
        } else {
            $db = new Database();
            $conn = $db->connect();
            $host = new Host($conn);
            $event = new Event($conn);
            $auth = $host->auth($host_name, $host_pass);
            # Test@pass1
            if ($auth) {
                $host_data = $host->getHost();
                $_SESSION['host_id'] = $host_data['host_id'];
                $_SESSION['host_name'] = $host_data['host_name'];
                $host_id = $_SESSION['host_id'];
                $event_data = [$host_id, $host_event];
                if ($event->exists()) {
                    if ($event->is_EventUnique($host_event)) {
                        $res = $event->insert($event_data);
                        $sm = "Angemeldet !";
                        Util::redirect("../PHP/homepage-host.php", "success", $sm, $data);
                    } else {
                        $em = "This Event is already taken";
                        Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
                    }
                } else {
                    $res = $event->insert($event_data);
                    $sm = "Angemeldet !";
                    Util::redirect("../PHP/homepage-host.php", "success", $sm, $data);
                }
            } else {
                $em = "Incorrect Hostname or Passwort";
                Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
            }
        }
    } else {
        $host_name = HostValidation::clean($_POST['hname']);
        $host_pass = HostValidation::clean($_POST['hpass']);

        // $data = "fname=" . $host_full_name . "&hname=" . $host_name . "&hemail=" . $host_email;

        if (!HostValidation::hostName($host_name)) {
            $em = "Invalid Host Name";
            Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
        } else if (!HostValidation::hostPassword($host_pass)) {
            $em = "Invalid Host Passwort";
            Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
        } else {
            $db = new Database();
            $conn = $db->connect();
            $host = new Host($conn);
            $auth = $host->auth($host_name, $host_pass);
            # Test@pass1
            if ($auth) {
                $host_data = $host->getHost();
                $_SESSION['host_id'] = $host_data['host_id'];
                $_SESSION['host_name'] = $host_data['host_name'];
                $sm = "Angemeldet !";
                Util::redirect("../PHP/homepage-host.php", "success", $sm, $data);
            } else {
                $em = "Incorrect Hostname or Passwort";
                Util::redirect("../PHP/anmelden-host.php", "error", $em, $data);
            }
        }
    }
} else {
    $em = "An error occured";
    Util::redirect("../PHP/anmelden-host.php", "error", $em);
}
