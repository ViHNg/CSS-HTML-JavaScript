<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: loginpage.php");
    exit();
}

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$username = $_SESSION['user'];
$folder_path = sprintf("/srv/mod2uploads/%s/*", $username);
$files = glob($folder_path);

$fileExists = false;
foreach ($files as $file) {
    $filename = basename($file);
    if (stripos($filename, $searchTerm) !== false) {
        // File name contains the search term (case-insensitive)
        $fileExists = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search results</title>
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
    <p id="title">Search results</p>
    
    <?php
    if ($fileExists) {
        echo "<p>File <strong>{$searchTerm}</strong> exists</p>";
    } else {
        echo "<p>File <strong>{$searchTerm}</strong> does not exist :(</p>";
    }
    ?>
    
    <a href="main.php">Back to File Directory</a>
</body>
</html>