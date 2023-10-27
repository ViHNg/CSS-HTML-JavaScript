<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamie and Vi's File Directory</title>
    <style>
        .ops {
            margin: 10px 0;
        }

        .ops-label {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <p id="title">File Directory</p>
    <form action="search.php" method="GET">
        <input id="search" type="text" name="search" placeholder="Search for a file">
        <input id="submit" type="submit" value="Search">
    </form>

    <form enctype="multipart/form-data" action="uploaded.php" method="POST">
        <input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
        <label for="uploadfile_input">Choose a file to upload:</label>
        <input name="uploadedfile" type="file" id="uploadfile_input" />
        <input type="submit" value="Upload File" />
    </form>

    <?php
    session_start();
    $username = $_SESSION['user'];
    $folder_path = sprintf("/srv/mod2uploads/%s/*", $username);
    $files = glob($folder_path);
    ?>

    <table>
        <tr>
            <th>Files:</th>
            <th></th>
            <th></th>
        </tr>
        <?php
        foreach ($files as $file) {
            $filename = basename($file);
            echo "\t<tr>\n\t\t<td>" . htmlentities($filename);
        ?>
            <td>
                <form name="view" action="view.php" method="POST">
                    <input type="hidden" name="filechosen" value="<?php echo $filename; ?>">
                    <input type="submit" name="view" value="view file">
                </form>
            </td>
            <td>
                <form name="delete" action="delete.php" method="POST">
                    <input type="hidden" name="filechosen" value="<?php echo $filename; ?>">
                    <input type="submit" name="delete" value="delete file">
                </form>
            </td>
        <?php
        }
        ?>
    </table>
</body>

</html>
