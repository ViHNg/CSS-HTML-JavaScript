<?php
session_start();

require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] == 'delete') {
    $comment_id = $_POST['comment_id'];

    $stmt = $mysqli->prepare("DELETE FROM Comments WHERE comment_id = ?");
    $stmt->bind_param('i', $comment_id);
    $stmt->execute();
    $stmt->close();

    header("Location: main.php");
    exit;
}
?>
