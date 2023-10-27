<?php
session_start();

require 'database.php';

// Check that user is logged in
if (!isset($_SESSION['user'])) {
    die("You need to be logged in to delete a story.");
}

if (!isset($_POST['post_id'])) {
    die("No story specified.");
}

$post_id = $_POST['post_id'];

$stmt = $mysqli->prepare("SELECT username FROM Stories WHERE post_id = ?");
$stmt->bind_param('i', $post_id);
$stmt->execute();
$result = $stmt->get_result();
$story = $result->fetch_assoc();

if ($story['username'] != $_SESSION['user']) {
    die("You can't delete a story that isn't yours.");
}

$stmt = $mysqli->prepare("DELETE FROM Stories WHERE post_id = ? AND username = ?");
$stmt->bind_param('is', $post_id, $_SESSION['user']);

if ($stmt->execute()) {
    header("Location: main.php?message=Story Deleted Successfully");
} else {
    echo "Error deleting the story.";
}

$stmt->close();
?>
 
