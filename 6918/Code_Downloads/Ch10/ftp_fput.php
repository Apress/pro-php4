<?php

// Open a pointer to a local file 
$file = 'data.txt';

// Append mode
$mode = 'r';    
                          
$file_pointer = fopen ($file, $mode)  
    or die ("Could not open file '$file' in mode '$mode'.");

// Skip the first 1k of the file 
$seek_position = 1024;fseek ($file_pointer, $seek_position)
    or die ("Could not seek to byte offset '$seek_position' in file '$file'");

// Connect to ftp server and authenticate 
$host = 'ftp.example.com';
$ftp_handle = ftp_connect ($host) 
    or die ("Could not connect to host '$host'.");

// Ensure that our ftp connection is closed 
register_shutdown_function (create_function ('', "ftp_quit ($ftp_handle);"));

$user = 'zak';
$pass = 'foo';
ftp_login ($ftp_handle, $user, $pass)   
    or die ("Could not authenticate as user '$user'.");

// Write the remaining data from local file pointer to a remote file 
$remote_file = 'remote_data.txt';
ftp_fput ($ftp_handle, $remote_file, $file_pointer, FTP_ASCII)  
   or die ("Could not upload data from file pointer to remote file " . 
           "'$remote_file' using ftp_fput().");
?>

