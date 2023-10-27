
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
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
<?php
session_start();

if(!isset($_SESSION['user']) || !isset($_POST['filechosen'])){
    echo "Invalid request!";
    exit();
}

$username = $_SESSION['user'];
$fileToDelete = basename($_POST['filechosen']); // Ensure only the filename part is taken

$filePath = "/srv/mod2uploads/{$username}/{$fileToDelete}";

if(file_exists($filePath)){
    if(unlink($filePath)){
        echo "File deleted successfully";
    } else {
        echo "Error deleting file";
    }
} else {
    echo "File not found!";
}

echo '<br><a href="main.php">Back to main page</a>';

?>
</body>
</html>

