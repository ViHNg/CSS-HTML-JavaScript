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
    <p id="title">Vi and Jamie's File Saving Site</p>
    <div class="container">
        <form method="POST">
            <label>Username : </label>
            <input type="text" placeholder="Enter Username" name="username" required>  
            <button type="submit" name="action" value="login">Login</button>

        </form>
        <form method="POST">
            <label>Create New User: </label>
            <input type="text" placeholder="Enter New Username" name="username" required>  
            <button type="submit" name="action" value="create">Create</button>
        </form>
        <form method="POST">
            <label>Delete User: </label>
            <input type="text" placeholder="Enter Username to Delete" name="username" required>  
            <button type="submit" name="action" value="delete">Delete</button>
        </form>
    </div>
</body>

<?php
if (isset($_POST['username'])) {
    $username = $_POST["username"];
    
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        echo "Invalid username";
        exit;
    }

    $predefined_users = file("/srv/mod2uploads/users.txt", FILE_IGNORE_NEW_LINES);

    // Check if user is trying to login
    if ($_POST['action'] == 'login') {
        if (in_array($username, $predefined_users)) {
            session_start();
            $_SESSION['user'] = $username;
            header("Location: main.php");
            exit();
        } else {
            echo "Username Not Found";
        }
    } 
    // Check if user is trying to create a new account
    elseif ($_POST['action'] == 'create') {
        if (in_array($username, $predefined_users)) {
            echo "Username already exists!";
        } else {
            file_put_contents("/srv/mod2uploads/users.txt", $username . "\n", FILE_APPEND);
            echo "User created successfully!";
        }
    }

    // Check if user is trying to delete an account
    elseif ($_POST['action'] == 'delete') {
        if (in_array($username, $predefined_users)) {
            $updated_users = array_diff($predefined_users, [$username]);
            file_put_contents("/srv/mod2uploads/users.txt", implode("\n", $updated_users) . "\n");
            echo "User deleted successfully!";
        } else {
            echo "Username not found!";
        }
    }
}
?>

