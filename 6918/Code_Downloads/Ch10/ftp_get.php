<?php

// Connect to ftp server and authenticate 
$host = 'ftp.example.com';
$ftp_handle = ftp_connect ($host)   
    or die ("Could not connect to host '$host'.");

// Ensure that our ftp connection is closed 
register_shutdown_function (create_function ('', "ftp_quit ($ftp_handle);"));

$local_file  = '/path/to/local_file.txt';
$remote_file = 'remote_data.txt';
ftp_get ($ftp_handle, $local_file, $remote_file, FTP_BINARY)   
   or die ("Could not copy remote file '$remote_file' to local file " .   
          "'$local_file'.");
?>
