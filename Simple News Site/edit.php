<?php
session_start();
require 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // get specific story using post_id
    $newTitle = $_POST['title'];
    $newLink = $_POST['link'];
    $postId = $_POST['post_id'];

    // query to update story in DB
    $stmt = $mysqli->prepare("UPDATE Stories SET title = ?, link = ? WHERE post_id = ?");
    // new title, link
    $stmt->bind_param('ssi', $newTitle, $newLink, $postId);
    $stmt->execute();
    $stmt->close();

    header("Location: main.php"); // Redirect back to the main page.
    exit;
}

// Assuming you're passing the post_id to this page via a POST request.
if (!isset($_POST['post_id'])) {
    // Handle error - maybe redirect or display a message
    die("Post ID not provided.");
}

// Get the story details.
$postId = $_POST['post_id'];

$stmt = $mysqli->prepare("SELECT title, link FROM Stories WHERE post_id = ?");
$stmt->bind_param('i', $postId);
$stmt->execute();
$stmt->bind_result($title, $link);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit PAge</title>
    <style>
        .ops{
            margin: 10px 0;
        }
        .ops-label{
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h2>Edit Story</h2>

    <form action="edit.php" method="POST">
        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">

        <label for="title">Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">

        <label for="link">Link:</label>
        <input type="url" id="link" name="link" value="<?php echo htmlspecialchars($link); ?>">

        <button type="submit" name="update" value="1">Update Story</button>
    </form>
</body>
</html>
