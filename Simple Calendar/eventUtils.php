<?php
require 'database.php';

session_start();
$token = $_SESSION['token'];

function fetchUserEvents($userID) {

    $stmt = $mysqli->prepare("SELECT title, DATE_FORMAT(date, '%m-%d-%Y') as date, time, event_id FROM Event WHERE user_id=?");
    if (!$stmt) {
        echo json_encode(array(
            "success" => false,
            "message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
        )); 
        
        exit;
    }
    

    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    $events = array();
    while ($row = $result->fetch_assoc()) {
        $row['date'] = htmlentities($row['date']);  
        $events[] = $row;
    }
    $stmt->close();

    return $events;
}
?>