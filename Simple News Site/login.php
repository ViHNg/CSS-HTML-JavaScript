<?php
session_start();
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
    <p id="title">The Daily JVs</p>
    <div class="container">
        <form method="POST">
            <label>Existing User: </label>
            <input type="text" placeholder="Enter Username" name="username" required> 
            <input type="password" placeholder="Enter Password" name="password" required>   
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
            <button type="submit" name="action" value="login">Login</button>
        </form>
        <form action="register.php" method="POST">
            <label>Register New User: </label>
            <button type="submit" name="action" value="register">Register</button>
        </form>
        <form action="delete.php" method="POST">
            <label>Delete User: </label>
            <button type="submit" name="action" value="delete">Delete</button>
        </form>
        <form method="POST">
            <label>Log in as Guest</label>
            <button type="submit" name="action" value="guest">Guest</button>
        </form>
    </div>
</body>

<?php
if (isset($_POST['username'])) {
    //Get the login info
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    //echo $rawpass;
    //echo $password;

    // Check valid username
    if (!preg_match('/^[\w_\-]+$/', $username)) {
        echo "Invalid username";
        exit;
    }

        // Get username and password from database
        require 'database.php';

        // select user id, username, and password
        $stmt = $mysqli->prepare("SELECT id, username, password FROM Users WHERE username LIKE ?"); // <<< CHANGED HERE
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        
        $stmt->bind_param('s',$username);
        $stmt->execute();
        
        $stmt->bind_result($userID, $checkuser, $checkpass);  // <<< CHANGED HERE
        
        $stmt->fetch();

        // Cross-check username and password
        if ($username == $checkuser && password_verify($password, $checkpass)) {
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $userID; 
            /*
            print_r($_SESSION);
            exit;    
            */        
            echo $userID;
            $_SESSION['token'] = bin2hex(random_bytes(32));
            header("Location: main.php");
            exit();
        }
         else {
            echo "Username Not Found or Wrong Password";
        }
   
    } 
    
    if ($_POST['action']=="guest") {
        //Get the login info
        $_SESSION['user'] = 'guest';
        header("Location: main.php");
    }
?>
