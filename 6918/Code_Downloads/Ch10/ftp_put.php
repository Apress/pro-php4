<?php

// Assume that we have already connected and authenticated to an FTP server 

$local_file  = '/path/to/local_file.txt';
$remote_file = 'remote_data.txt';

ftp_get ($ftp_handle, $local_file, $remote_file, FTP_BINARY)
    or die ("Could not copy remote file '$remote_file' to local file " . "'$local_file'.");
?>
