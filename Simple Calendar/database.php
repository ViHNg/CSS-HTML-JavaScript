
<?php
// Content of database.php

$mysqli = new mysqli('localhost', 'mod3', 'mod3', 'Calendar');

if($mysqli->connect_errno) {
    echo json_encode(array(
		"success" => false,
		"message" => sprintf("Connection Failed: %s\n", htmlentities($mysqli->connect_error))
    ));
    
	exit;
}

?>