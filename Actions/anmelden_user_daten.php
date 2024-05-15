<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $user_email = UserValidation::clean($_POST['uemail']);
    $user_pass = UserValidation::clean($_POST['upassword']);

    // $data = "fname=" . $host_full_name . "&hname=" . $host_name . "&hemail=" . $host_email;

    if (!UserValidation::userEmail($user_email)) {
        $em = "Invalid User Email";
        Util::redirect("../index.php", "error", $em, $data);
    } else if (!UserValidation::userPassword($user_pass)) {
        $em = "Invalid User Passwort";
        Util::redirect("../index.php", "error", $em, $data);
    } else {
        $db = new Database();
        $conn = $db->connect();
        $user = new User($conn);
        $auth = $user->auth($user_email, $user_pass);
        # Test@pass1
        if ($auth) {
            $user_data = $user->getUser();
            $_SESSION['user_id'] = $user_data['user_id'];
            $_SESSION['user_email'] = $user_data['user_email'];
            $sm = "Angemeldet !";
            Util::redirect("../PHP/homepage-user.php", "success", $sm, $data);
        } else {
            $em = "Incorrect User Email or Password";
            Util::redirect("../index.php", "error", $em, $data);
        }
    }
} else {
    $em = "An error occured";
    Util::redirect("../index.php", "error", $em);
}
