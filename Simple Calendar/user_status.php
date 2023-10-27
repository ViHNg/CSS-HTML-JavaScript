<?php
// this is so the user stays logged in when refreshhed
// check if a user is logged in by checking the session data for their username
session_start();
header("Content-Type: application/json");

if (isset($_SESSION['username'])) {
    echo json_encode(array(
        "loggedIn" => true,
        "username" => $_SESSION['username']
    ));
    exit;
} else {
    echo json_encode(array(
        "loggedIn" => false
    ));
    exit;
}
?>