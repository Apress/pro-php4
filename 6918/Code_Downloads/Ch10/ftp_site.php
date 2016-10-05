<?php

// Assume that we have already connected and authenticated to an FTP server 

// Try to change the permissions of a remote file 
$command = 'chmod 0755 /path/to/file.txt';

ftp_site ($ftp_handle, $command)
    or die ("Command '$command' could not be run.");

echo("Command '$command' was run successfully.");
?>
