<?php

ni_set("session.cookie_httponly", 1);
session_start();

header("Content-Type: application/json"); 

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


$format = $json_obj['format'];
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

require 'eventUtils.php';

// if (!isset($_SESSION['token']) || !isset($_GET['token']) || $_SESSION['token'] !== $_GET['token']) {
//     echo json_encode(["success" => false, "message" => "Invalid token"]);
//     exit();
// }

if (isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];
    $events = fetchUserEvents($userID);
} else {
    echo json_encode(array(
        "success" => false,
        "message" => htmlentities("User not logged in")
    ));
       
    exit();
}


header('Content-Disposition: attachment; filename=events.' . $format);

if ($format === 'csv') {
    header('Content-Type: text/csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, array_keys($events[0])); // headers
    foreach ($events as $event) {
        fputcsv($out, $event);
    }
    fclose($out);
} else {
    header('Content-Type: application/json');
    echo json_encode(array(
            "success" => false,
            "events" => $events
        ));
}
?>
