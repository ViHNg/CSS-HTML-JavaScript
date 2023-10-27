<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_POST['filechosen'])) {
    header("Location: loginpage.php");
    exit();
}

$filechosen = $_POST['filechosen'];
$username = $_SESSION['user'];
$full_path = sprintf("/srv/mod2uploads/%s/%s", $username, $filechosen);

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($full_path);
//echo $full_path;
//echo $mime;

header("Content-Type: " .$mime);
header('content-disposition: inline; filename="' .$filechosen . '";');
readfile($full_path);
?>
