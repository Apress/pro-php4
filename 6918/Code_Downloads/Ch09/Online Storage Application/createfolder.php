<?php

include("common.php");
session_start();

if (!session_is_registerd("isAuthenticated") || !$isAuthenticated) {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}

// Create the Folder
if (createFolder($currentFolder, $foldName) < 0) {
    sendErrorPage("Internal Error: Could not create folder " . $foldName);
    exit;
}

// Create a file for storing mime-types
    $mimeTypeFile = $currentFolder . "/" . $foldName . "/" . "mimeTypes";
    fopen(getAbsolutePath($mimeTypeFile), "w+");

include_once("Main.php");
?>
