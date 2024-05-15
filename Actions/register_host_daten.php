<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $host_full_name = HostValidation::clean($_POST['hfullname']);
    $host_name = HostValidation::clean($_POST['hname']);
    $host_email = HostValidation::clean($_POST['hemail']);
    $host_pass = HostValidation::clean($_POST['hpass']);
    $host_foto = HostValidation::clean($_POST['hfoto']);

    $data = "fname=" . $host_full_name . "&hname=" . $host_name . "&hemail=" . $host_email;

    if (!HostValidation::hostFullName($host_full_name)) {
        $em = "Your Name is Invalid";
        Util::redirect("../PHP/register-host.php", "error", $em, $data);
    } else if (!HostValidation::hostName($host_name)) {
        $em = "Invalid Host Name";
        Util::redirect("../PHP/register-host.php", "error", $em, $data);
    } else if (!HostValidation::hostEmail($host_email)) {
        $em = "Invalid Host Email";
        Util::redirect("../PHP/register-host.php", "error", $em, $data);
    } else if (!HostValidation::hostPassword($host_pass)) {
        $em = "Invalid Host Passwort";
        Util::redirect("../PHP/register-host.php", "error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $host = new Host($conn);
        if ($host->exists()) {
            if ($host->is_HostUnique($host_name)) {
                # password hash
                # Test@pass1
                $host_password = password_hash($host_pass, PASSWORD_DEFAULT);
                $host_data = [$host_full_name, $host_name, $host_email, $host_password, $host_foto];
                $res = $host->insert($host_data);

                if ($res) {
                    $sm = "Successfully registered!";
                    Util::redirect("../PHP/register-host.php", "success", $sm, $data);
                } else {
                    $em = "An error occurred";
                    Util::redirect("../PHP/register-host.php", "error", $em, $data);
                }
            } else {
                $em = "The hostname ($host_name) is already taken";
                Util::redirect("../PHP/register-host.php", "error", $em, $data);
            }
        } else {
            $host_password = password_hash($host_pass, PASSWORD_DEFAULT);
            $host_data = [$host_full_name, $host_name, $host_email, $host_password, $host_foto];
            $res = $host->insert($host_data);

            if ($res) {
                $sm = "Successfully registered!";
                Util::redirect("../PHP/register-host.php", "success", $sm, $data);
            } else {
                $em = "An error occurred from insert";
                Util::redirect("../PHP/register-host.php", "error", $em, $data);
            }
        }
    }
} else {
    $em = "An error occured from Post";
    Util::redirect("../PHP/register-host.php", "error", $em);
}
