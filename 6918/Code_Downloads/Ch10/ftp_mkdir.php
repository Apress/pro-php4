<?php

// Assume that we have already connected and authenticated to an FTP server 

$directory = 'foo';

$created_dir = ftp_mkdir ($ftp_handle, $directory)
    or die ("Could not create directory '$directory'.");

echo("Directory '$created_dir' was created. We asked the FTP server to create a directory named '$directory'.");
?>

