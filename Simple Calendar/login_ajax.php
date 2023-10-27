<?php


header("Content-Type: application/json"); 

$json_str = file_get_contents('php://input');

$json_obj = json_decode($json_str, true);

$username = $json_obj['username'];
$password = $json_obj['password'];


if (!preg_match('/^[\w_\-]+$/', $username)) {
    echo json_encode(array(
		"success" => false,
		"message" => htmlentities("Invalid Username")
    ));
    exit;
}
require 'database.php';

// select user id, username, and password
$stmt = $mysqli->prepare("SELECT id, username, password FROM Users WHERE username LIKE ?"); // <<< CHANGED HERE
if(!$stmt){
	echo json_encode(array(
		"success" => false,
		"message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
    )); 
	
	exit;
}

$stmt->bind_param('s',$username);
$stmt->execute();

$stmt->bind_result($userID, $checkuser, $checkpass);  

$stmt->fetch();

if ($username == $checkuser && password_verify($password, $checkpass)) {
	ini_set("session.cookie_httponly", 1);
	session_start();
	$_SESSION['username'] = $username;
	$_SESSION['user_id'] = $userID;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 
	
	echo json_encode(array(
		"success" => true,
		"token" => htmlentities($_SESSION['token'])
	));
	exit;
}
else {
	echo json_encode(array(
		"success" => false,
		"message" => htmlentities("Username Not Found or Wrong Password")
	));
	exit;
	
}

?>