<?php

include("common.php");
if (!isSessionAuthenticated() {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}

// Remove the Folder
if (is_dir(getAbsolutePath($currentFolder . "/" . $foldName))) {	
    if (deleteFolder($currentFolder, $foldName) < 0) {
        sendErrorPage("Internal Error: Could not delete folder " . $foldName);
	exit;
    }
} else {
    if (deleteFile($currentFolder, $foldName) < 0) {
        sendErrorPage("Internal Error: Could not delete file " . $foldName);
	exit;
    }
	
    // Remove the entry of the file from the mimeTypes
    $mimeTypeFile = getAbsolutePath($currentFolder . "/" . "mimeTypes");
    if (($fileContent = file($mimeTypeFile)) == null) {
        sendErrorPage("Internal Error: Could not read file " . $mimeTypeFile);
	exit;
    }
    if (($fp = fopen($mimeTypeFile, "w")) <= 0) {
        sendErrorPage("Internal Error: Could not open file " . $mimeTypeFile);
	exit;
    }

    for($i=0; $i < sizeof($fileContent); $i++ ) {
        $line = trim($fileContent[$i]);
        list ($fileName, $mimeType) = split(":", $line);
     
        if ($fileName != $foldName) {
            fwrite($fp, $fileContent[$i]);
        }
    }

    fclose($fp);
}

include_once("main.php");
?>
