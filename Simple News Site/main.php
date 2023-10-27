<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
    <form method="POST">
        <button id="logout" type="submit" name="action" value="logout">logout</button>
    </form>
    <p id="title">The Daily JVs</p>
    <form action="add_stories.php" method="POST" enctype="multipart/form-data">
        <label>Add Stories: </label>
        <button type="submit" name="action" value="add">add</button>
    </form>

<?php

if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Clear session data
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit; //halt script
}

$username = $_SESSION['user'];

require 'database.php';

// Select all fields from stories table
$stmt = $mysqli->prepare("select * from Stories");
if (!$stmt) {
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}

$stmt->execute();
$result = $stmt->get_result();

echo '<div class="storiescontainer">';

while ($row = $result->fetch_assoc()) {
    echo '<div class="storycard">';
    echo '<h3 class="storytitle"><i>'. htmlspecialchars($row["title"]) .'</i></h3>';
    echo '<p><span class="postername">Posted by ' . htmlspecialchars($row["username"]) . '</span></p>';
    echo '<p class="clicktoreadmore"><u><a href="'. htmlspecialchars($row["link"]) . '" target="_blank">'. "click to read more ->" .'</a></u></p>';

    $storyTitle = htmlspecialchars($row["title"]);
    $shareText = "check out this story! : $storyTitle";
    $twitterShareLink = "https://twitter.com/intent/tweet?text=" . urlencode($shareText);

    // Output the Twitter share link
    echo '<p class="twitter"><u><i><a href="' . $twitterShareLink . '" target="_blank">Share on Twitter</a></i></u></p>';


    if ($row["username"] == $_SESSION['user'] && $username != 'guest') {
        echo '<div class="deleteeditcomments">';
        echo '<form action="delete_stories.php" method="POST" style="display: inline;">';
        echo '<input type="hidden" name="post_id" value="' . $row["post_id"] . '">';
        echo '<input type="submit" value="Delete Story">';
        echo '</form>';

        echo '<form  action="edit.php" method="POST" style="display: inline;">';
        echo '<input type="hidden" name="post_id" value="'. $row["post_id"] .'">';
        echo '<button type="submit">Edit Story</button>';
        echo '</form>';
        echo '</div>';
    }

    $commentsStmt = $mysqli->prepare("SELECT comment_id, username, comment, timestamp, likes FROM Comments WHERE post_id = ?");
    $commentsStmt->bind_param('i', $row["post_id"]);
    $commentsStmt->execute();    
    
    $commentsResult = $commentsStmt->get_result();
    
    while ($commentRow = $commentsResult->fetch_assoc()) {
        echo "<div class='comment'>";
        echo "<small>" . htmlspecialchars($commentRow["username"]) . ":</small>";
        echo "<small>Posted on " . htmlspecialchars($commentRow["timestamp"]) . "</small>";
        echo "<p>" . htmlspecialchars($commentRow["comment"]) . "</p>";
    
        // Display likes count for the comment
        echo "<div class='likes-display'>";
        echo htmlspecialchars($commentRow["likes"]) . " Likes";
        echo "</div>";

        // verifying that curr logged in user is same as user who posted the comment
        if ($username == $commentRow["username"]) {
            echo '<form action="editcomment.php" method="GET" style="display: inline;">';
            echo '<input type="hidden" name="post_id" value="'. $row["post_id"] .'">';
            echo '<input type="hidden" name="comment_id" value="'. $commentRow["comment_id"] .'">';
            echo '<button type="submit">Edit Comment</button>';
            echo '</form>';

            echo '<form action="deletecomment.php?post_id='. $row["post_id"] .'" method="POST" style="display: inline;">';
            echo '<input type="hidden" name="comment_id" value="'. $commentRow["comment_id"] .'">';
            echo '<button type="submit" name="action" value="delete">Delete Comment</button>';
            echo '</form>';
        }
        echo "</div>";
    }
    
    if ($username != 'guest') {
        echo '<form action="comment.php?post_id='. $row["post_id"] .'" method="POST">';
        echo '<input type="hidden" name="post_id" value="'. $row["post_id"] .'">';
        echo '<textarea name="comment" required placeholder="write your comment..."></textarea>';
        echo '<button type="submit">Submit Comment</button>';
        echo '</form>';
    }
    echo '</div>';
}

echo '</div>';
$stmt->close();

if (isset($_POST['action']) && $_POST['action']=='add'){
    if ($username != 'guest') {
        header("Location: add_stories.php");
        exit;
    } else {
        echo "Guests can't add stories!";
    }
}
?>

</body>
