<?php

function removeDirectory($directory) 
{
    $dir = opendir($directory);
    while (($file = readdir($dir))) {
        if (is_file($directory . "/" . $file)) {
            unlink($directory . "/" .$file);
        } else if (is_dir($directory . "/" .$file) &&
            ($file != ".") && ($file != "..")) {
            removeDirectory($directory . "/" . $file);
        }
    }
    closedir($dir);
    rmdir($directory);
    printf("Directory %s removed", $directory);
}
removeDirectory("c:/temp/test");
?>
