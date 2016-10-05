<?php

include("common.php");
if (!isSessionAuthenticated()) {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}

// upload the file
move_uploaded_file($HTTP_POST_FILES["userfile"]["tmp_name"], getAbsolutePath($currentFolder . "/" . basename($HTTP_POST_FILES["userfile"]["name"])));

// Add the mime-type of the file in the mimeTypes file
$fp = fopen(getAbsolutePath($currentFolder . "/" . "mimeTypes"), "a");
fwrite($fp, basename($HTTP_POST_FILES["userfile"]["name"]) . ":" . $HTTP_POST_FILES["userfile"]["type"] . "\n");

fclose($fp);

include_once("main.php");
?>
