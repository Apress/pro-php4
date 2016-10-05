<?php

// Assume that we have already connected and authenticated to an FTP server 

$directory = 'temp';
ftp_rename ($ftp_handle, $directory)
    or die ("Directory '$directory' could not be removed.");

echo("Directory '$directory' was removed.");
?>
