<?php
ini_set("session.cookie_httponly", 1);
session_start();

header("Content-Type: application/json"); 

$json_str = file_get_contents('php://input');

$json_obj = json_decode($json_str, true);

$newtitle = $json_obj['newtitle'];
$newdate = $json_obj['newdate'];
$newtime = $json_obj['newtime'];
$eventID = $json_obj['eventID'];


$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];

require 'database.php';

$stmt = $mysqli->prepare("select user_id from Event where event_id like ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error)),
            "token" => htmlentities($_SESSION['token'])
        )); 
    }
    $stmt->bind_param('s', $eventID);
    $stmt->execute();
    $stmt->bind_result($checkuser);
    $stmt->fetch();
    $stmt->close();

// Check user
if ($userID == $checkuser){
    $stmt = $mysqli->prepare("UPDATE Event SET title = ?, date =?, time = ? WHERE event_id = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
        )); 
        exit;
    }
    $stmt->bind_param('ssss', $newtitle, $newdate, $newtime, $eventID);
    $stmt->execute();
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
        "message" => sprintf("You cannot edit this event: %s",htmlentities($userID))

    )); 
    exit;
}


?>