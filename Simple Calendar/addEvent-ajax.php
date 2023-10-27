<?php
ini_set("session.cookie_httponly", 1);
session_start();

header("Content-Type: application/json"); 

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);


$title = $json_obj['title'];
$date = $json_obj['date'];
$time = $json_obj['time'];


$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];
$token = $_SESSION['token'];


// Insert Event into Database 
require 'database.php';
$stmt = $mysqli->prepare("insert into Event (user_id, username, title, date, time) values (?,?,?,?,?)");
if(!$stmt){
    echo json_encode(array(
		"success" => false,
		"message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
    )); 
    
    exit;
}
$stmt->bind_param('issss', $userID, $username, $title, $date, $time);

$stmt->execute();

$stmt->close();
echo json_encode(array(
    "success" => true,
    "token" => htmlentities($token)
));
exit;

?>