<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Page</title>
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
</body>

 <?php
session_start();

if(!isset($_SESSION)){
    header("Location: loginpage.php");
    exit();
}

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$username = $_SESSION['user'];

$user_dir = "/srv/mod2uploads/$username";

if (!file_exists($user_dir)) {
    mkdir($user_dir, 0755, true);
}

// Get the filename and make sure it is valid
$filename = basename($_FILES['uploadedfile']['name']);
echo $filename."\n";
if(!preg_match('/^[\w_\.\-]+$/', $filename) ){
    echo "Invalid Filename";
    exit;
}

//   $hidden = dirname(__DIR__); echo $hidden;
$full_path = sprintf("/srv/mod2uploads/%s/%s", $username, $filename);
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)){
    echo "Successfully uploaded";
    
    // print_r($_FILES); // for debugging purposes
}

/*
//check if files were moved from temp location
if (!move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $full_path)) {
    echo 'File was not moved. Error: ' . $_FILES['uploadedfile']['error'];
} else {
    echo "Successfully uploaded";
}
*/

?>

