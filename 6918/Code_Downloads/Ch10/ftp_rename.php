<?php

// Assume that we have already connected and authenticated to an FTP server 

$old = 'original.txt';
$new = 'backup.txt';
ftp_rename ($ftp_handle, $old, $new)
    or die ("File '$old' could not be renamed to '$new'.");
?>
