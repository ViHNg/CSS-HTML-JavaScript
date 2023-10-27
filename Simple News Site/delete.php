
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User</title>
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
    <label>Username for deletion: </label>
    <input type="text" placeholder="Enter Username" name="username"required>  
    <label>Password for deletion: </label>
    <input type="password" placeholder="Enter password" name="password"required>  
    <button type="submit" name="action" value="register">Delete</button>
</form>
<form method="POST">
    <button type="submit" name="action" value="back">Back to Login Page</button>
</form>
    </body>
<?php
// Get register info
if (isset($_POST['username'])) {
    // Get User info
    $username = $_POST["username"];
    $password =$_POST['password'];
    // Check valid username
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        echo "Invalid username";
        exit;
    }

    // Get username and password
    require 'database.php';

    $stmt = $mysqli->prepare("select username, password from Users where username like ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('s',$username);
    $stmt->execute();

    $stmt->bind_result($checkuser, $checkpass);
    
    $stmt->fetch();
    $stmt->close();
    
    // Cross-check username and password
    if ($username == $checkuser & password_verify($password,$checkpass)) {
        $stmt = $mysqli->prepare("delete from Users where username like ?");
        $stmt->bind_param('s',$username);
        $stmt->execute();
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
    
        echo "User deleted successfully";
        exit();
    } else {
        echo "Username Not Found or Wrong Password";
    }
}  
    // Back to login page
    if ($_POST['action'] == 'back') {
        header("Location: login.php");
        exit;
    }     
?>
