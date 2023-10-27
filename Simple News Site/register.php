
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
    <label>Username: </label>
    <input type="text" placeholder="Enter Username" name="username"required>  
    <label>Password: </label>
    <input type="password" placeholder="Enter password" name="password"required>  
    <button type="submit" name="action" value="register">Register</button>
</form>
<form method="POST">
    <button type="submit" name="action" value="back">Back to Login Page</button>
</form>
    </body>
<?php
// Get register info
if (isset($_POST['username'])) {
    $newuser = $_POST["username"];
    $rawpass =$_POST['password'];
    // Check valid username
    if (!preg_match('/^[\w_\-]+$/', $newuser)) {
        echo "Invalid username";
        exit;
    }
    if (!preg_match('/^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{9,}$/',$rawpass)){
        echo "Invalid password! A Password should have at least 8 characters, a number and a special character.";
        exit;
    }
    // Hash into new password
    $newpass = password_hash($rawpass,PASSWORD_BCRYPT);

    
    if ($_POST['action'] == 'register') {
        session_start();
        $_SESSION['user'] = $newuser;
        require 'database.php';

        $stmt = $mysqli->prepare("insert into Users (username, password) values (?,?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('ss', $newuser, $newpass);

    $stmt->execute();

    $stmt->close();

    } 
}  
    if ($_POST['action'] == 'back') {
        header("Location: login.php");
        exit;
    }     
?>
