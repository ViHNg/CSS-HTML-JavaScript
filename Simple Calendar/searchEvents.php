<?php
ini_set("session.cookie_httponly", 1);
session_start();

header("Content-Type: application/json");

// fetch the json data
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$query = $json_obj['query'];


require 'database.php';  

$stmt = $mysqli->prepare("SELECT * FROM Event WHERE title LIKE ?");

if (!$stmt) {
    echo json_encode(array(
        "success" => false,
        "message" => "Query Prep Failed: " . htmlentities($mysqli->error)
    ));
    exit;
}

$searchTerm = "%" . $query . "%";
$stmt->bind_param('s', $searchTerm);

$stmt->execute();

$result = $stmt->get_result();
$events = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();

// output results as a json array
echo json_encode(array(
    "success" => true,
    "events" => $events
));
exit;
?>