<?php

include("common.php");
if (!isSessionAuthenticated()) {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}

// Find the mime type of the file
$mimeTypeFile = getAbsolutePath($currentFolder . "/" . "mimeTypes");
if (($fileContent = file($mimeTypeFile)) == null) {
    sendErrorPage("Internal Error: Could not read file " . $mimeTypeFile);
    exit;
}

for($i=0; $i < sizeof($fileContent); $i++ ) {
    $line = trim($fileContent[$i]);
    list ($fileName, $mimeType) = split(":", $line);
    if ($fileName == $file) {
        $contentType= $mimeType;
        break;
    }
}

if (isset($contentType)) {
    header("Content-Type :".$contentType);
}

$fileAbsPath = getAbsolutePath($currentFolder . "/" . $file);	

if (isset($contentType) && strstr($contentType, "text/")) {
    $fp = fopen($fileAbsPath, "r");
} else {   
    $fp = fopen($fileAbsPath, "rb");
}

fpassthru($fp);
?>