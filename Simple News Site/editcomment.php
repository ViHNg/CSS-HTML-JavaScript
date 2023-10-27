<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database.php';

$comment_id = $_GET['comment_id'];

// Fetch the comment to be edited
if (isset($_GET['comment_id'])) {
    $stmt = $mysqli->prepare("SELECT comment FROM Comments WHERE comment_id = ?");
    $stmt->bind_param('i', $comment_id);
    $stmt->execute();
    $stmt->bind_result($comment);
    $stmt->fetch();
    $stmt->close();
}

// Handle the form submission to update the comment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $edited_comment = $_POST['edited_comment'];

    $stmt = $mysqli->prepare("UPDATE Comments SET comment = ? WHERE comment_id = ?");
    $stmt->bind_param('si', $edited_comment, $comment_id);
    $stmt->execute();
    $stmt->close();
    header("Location: comment.php?post_id=" . $_POST['post_id']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Comment</title>
</head>
<body>
    <form method="POST">
        <textarea name="edited_comment"><?php echo htmlspecialchars($comment); ?></textarea>
        <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
        <input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>">
        <input type="submit" value="Submit Edit">
    </form>
</body>
</html>
