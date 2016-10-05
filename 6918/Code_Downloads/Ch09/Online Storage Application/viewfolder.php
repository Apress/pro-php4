<?php

include("common.php");
if (!isSessionAuthenticated()) {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}

session_register("currentFolder");
// Set the current Folder
if ($fold == ".") {
    // Do nothing
} else if ($fold == "..") {
    $pos = strrpos($currentFolder, "/");	
    $currentFolder = substr($currentFolder, 0, $pos);
} else {
    $currentFolder = $currentFolder . "/" . $fold;
}

// Current folder should always contain $username
if (!strstr(strtoupper($currentFolder), strtoupper($username))) {
    sendErrorPage("Error: Cannot access " . $currentFolder);
    exit;
}

include_once("main.php");
?>
