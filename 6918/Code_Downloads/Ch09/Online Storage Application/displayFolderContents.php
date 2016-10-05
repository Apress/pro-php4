<?php

include("common.php");

if (!isAuthenticated()) {
    //Send error page
    echo("User is not Authenticated");
    exit;
}
?>

<html>
  <head>	
    <title> Online Storage Application </title>
  </head>	

  <body>
    <?php
    if (isset($selectedFolder)) {
        $currentFolder = $currentFolder . "/" . $selectedFolder;
    }
    printf("<h1> %s </h1>", $currentFolder);
    // Read the contents of the current Folder and Display them
    if (($dir = opendir(getAbsolutePath($currentFolder))) < 0) {
        // Internal error
    }

    while (($file = readdir($dir)) != false) {
        if (is_dir(getAbsolutePath($currentFolder . "/" . $file))) {
	    printf("%s", makeAnchorElement("DisplayFolderContents.php4?selectedFolder=".$file, $file));
	} else {
	    printf("%s", $file);
	}
	printf("<br>");
    }
    ?>
  </body>
</html>
