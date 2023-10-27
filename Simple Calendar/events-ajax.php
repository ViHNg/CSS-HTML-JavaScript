<?php
ini_set("session.cookie_httponly", 1);
session_start();
// this file is just to fetch all events for a given month for the current logged-in user from the database

require 'eventUtils.php';

header("Content-Type: application/json");

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);
$month = $json_obj['month'];
$year = $json_obj['year'];
$loggedIn = $json_obj['loggedIn'];

require 'database.php';

function formatDateToMDY($date) {
    return date("m-d-Y", strtotime($date));
}

if ($loggedIn && isset($_SESSION['user_id'])) {
    $userID = $_SESSION['user_id'];

    // query to fetch all events for the given month for the user
    $stmt = $mysqli->prepare("SELECT title, DATE_FORMAT(date, '%m-%d-%Y') as date, time, event_id FROM Event WHERE user_id=? AND YEAR(date)=? AND MONTH(date)=?");

    if (!$stmt) {
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed: " . htmlentities($mysqli->error)
        ));
        exit;
    }

    // binding parameters are all ints
    $stmt->bind_param('iii', $userID, $year, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = array();
    while ($row = $result->fetch_assoc()) {
        $row['date'] =htmlentities($row['date']);  
        $events[] = $row;
    }

    $stmt->close();

    echo json_encode(array(
        "success" => true,
        "events" => $events 
        // Can't htmlentities here as it will affect the render(), 
        // So we have escape output when building events from row above
    ));
    exit;
} else {
    echo json_encode(array(
        "success" => false,
        "events" => []

    ));
    exit;
}

?>