<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stories</title>
</head>
<body>
    <form method="POST">
        <button type="submit" name="action" value="back">back</button>
    </form>
    <p id="title">The Daily JVs</p>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <label>Add your stories: </label>
            <input type="text" placeholder="Enter Title" name="title" required> 
            <input type="text" placeholder="Enter Description" name="body" required>   
            <input type="text" placeholder="Enter Link" name="link" required>   
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>">
            <button type="submit" name="action" value="add">Add</button>
        </form>

        <?php
        // back button
        if (isset($_POST['action']) && $_POST['action'] == 'back') {
            header("Location: main.php");
            exit;
        }

        if (isset($_POST['action']) && $_POST['action'] == 'add') {
            $username = $_SESSION['user'];
            $title = $_POST['title'];
            $body = $_POST['body'];
            $link = $_POST['link'];
            
            require 'database.php';
            
            $stmt = $mysqli->prepare("select id from Users where username like ?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();
            
            $stmt = $mysqli->prepare("insert into Stories (user_id, username, title, body, link) values (?,?,?,?,?)");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('issss', $user_id, $username, $title, $body, $link);
            $stmt->execute();
            $stmt->close();        
        } 
        
        ?> 
    </div>
</body>
</html>
