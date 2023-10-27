<?php
session_start();

require 'database.php';

$post_id = $_GET['post_id'];

if (isset($_POST['like'])) {
    $comment_id = $_POST['comment_id'];

    $stmt = $mysqli->prepare("UPDATE Comments SET likes = likes + 1 WHERE comment_id = ?");
    $stmt->bind_param('i', $comment_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        // You can log the error here
    }
    $stmt->close();
    header("Location: comment.php?post_id=" . $post_id); // Redirect to avoid form re-submission on refresh
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    $username = $_SESSION['user'];

    if (!empty($comment) && !empty($username)) {
        $stmt = $mysqli->prepare("INSERT INTO Comments (post_id, username, comment) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $post_id, $username, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

$comments = $mysqli->prepare("SELECT comment_id, username, comment, timestamp, likes FROM Comments WHERE post_id = ? ORDER BY timestamp DESC");
$comments->bind_param('i', $post_id);
$comments->execute();
$result = $comments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments page <?php echo htmlspecialchars($post_id); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST">
        <button type="submit" name="action" value="back">back</button>
    </form>
    <h2>Comments</h2>

    <?php
    if (isset($_POST['action']) && $_POST['action'] == 'back') {
        header("Location: main.php");
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        ?>
        <div class='comment'>
            <p><?php echo htmlspecialchars($row["comment"]); ?></p>
            <small>Posted by <?php echo htmlspecialchars($row["username"]); ?> on <?php echo $row["timestamp"]; ?></small>

            <div class='likes-section'>
                <span><?php echo $row['likes']; ?> likes</span>
                <form method='POST'>
                    <input type='hidden' name='comment_id' value='<?php echo $row['comment_id']; ?>'>
                    <input type='submit' name='like' value='Like'>
                </form>
            </div>
        </div>
        <?php
    }
    ?>
    
    <form action="comment.php?post_id=<?php echo htmlspecialchars($post_id); ?>" method="POST">
        <textarea name="comment" required placeholder="write your comment..."></textarea>
        <button type="submit">Submit Comment</button>
    </form>
</body>
</html>
