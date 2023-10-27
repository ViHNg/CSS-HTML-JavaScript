<?php
ini_set("session.cookie_httponly", 1);
session_start();

header("Content-Type: application/json"); 

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$eventID = $json_obj['eventID'];


$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];

require 'database.php';

$stmt = $mysqli->prepare("select user_id from Event where event_id like ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
        )); 
    }
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $stmt->bind_result($checkuser);
    $stmt->fetch();
    $stmt->close();

// check if user id matches the event id (to make sure user has permissions to delete it)
if ($userID == $checkuser){
    $stmt = $mysqli->prepare("DELETE FROM Event WHERE event_id = ?");
    error_log("Trying to delete event with ID: " . $eventID);

    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
        )); 
        exit;
    }
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    
    // Debugging Area, please ignore
    if($stmt->affected_rows == 0){
        error_log("No rows were deleted.");
    } else {
        error_log($stmt->affected_rows . " row(s) were deleted.");
    }
    
    $stmt->close();
    echo json_encode(array(
        "success" => true,
        "token" => htmlentities($_SESSION['token'])
    ));
    exit;
    }
    else{
        echo json_encode(array(
            "success" => false,
            "message" => htmlentities("You cannot delete this event")

    )); 
    exit;
}  

?>