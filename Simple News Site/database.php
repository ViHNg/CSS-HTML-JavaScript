<?php
// Content of database.php

$mysqli = new mysqli('localhost', 'mod3', 'mod3', 'News');

if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}

?>