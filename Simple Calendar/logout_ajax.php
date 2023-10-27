<?php
ini_set("session.cookie_httponly", 1);
session_start();
header("Content-Type: application/json");


$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
    session_destroy();
    echo json_encode(array(
        "success" => true
    ));
} else {
    echo json_encode(array(
        "success" => false
    ));
}
exit;
?>