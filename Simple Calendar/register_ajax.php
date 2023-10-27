<?php

header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json


$json_str = file_get_contents('php://input');

$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$newuser = $json_obj['newuser'];
$newpass = $json_obj['newpass'];


// Check to see if the username and password are valid.  (You learned how to do this in Module 3.)
if (!preg_match('/^[\w_\-]+$/', $newuser)) {
    echo json_encode(array(
		"success" => false,
		"message" => htmlentities("Invalid Username")
    ));
    exit;
}
// Check valid password 
if (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{9,}$/',$newpass)){
    echo json_encode(array(
		"success" => false,
		"message" => htmlentities("Invalid password! A Password should have at least 8 characters, a number and a special character.")
    )); 
    exit;
}
require 'database.php';
// Check if username already exists
$stmt_check = $mysqli->prepare("SELECT id FROM Users WHERE username LIKE ?"); // <<< CHANGED HERE
if(!$stmt_check){
	echo json_encode(array(
		"success" => false,
		"message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
    )); 
	
	exit;
}

$stmt_check->bind_param('s',$newuser);
$stmt_check->execute();


$stmt_check->bind_result($checkid); 

$stmt_check->fetch();
$stmt_check-> close();

if ($checkid){
  echo json_encode(array(
		"success" => false,
		"message" => htmlentities(sprintf("Username already exists %s",$checkid))
  )); 
  exit;
}

$hashedpass = password_hash($newpass,PASSWORD_BCRYPT);

// Remove this so that you have to log in to start session
// ini_set("session.cookie_httponly", 1);
// session_start();

// $_SESSION['username'] = $newuser;
// $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 


$stmt = $mysqli->prepare("insert into Users (username, password) values (?,?)");
if(!$stmt){
    echo json_encode(array(
		"success" => false,
		"message" => sprintf("Query Prep Failed: %s\n", htmlentities($mysqli->error))
    )); 
    
    exit;
}
$stmt->bind_param('ss', $newuser, $hashedpass);

$stmt->execute();

$stmt->close();
echo json_encode(array(
    "success" => true,
    "message" => htmlentities(sprintf("Username not exists %s",($checkid.is_null())))
));
exit;

?>