<?php

// Open a pointer to a local file 
$file = 'data.txt';
$mode = 'a';                  // append mode

$file_pointer = fopen ($file, $mode)
    or die ("Could not open file '$file' in mode '$mode'.");

// Connect to ftp server and authenticate 
$host = 'ftp.example.com';
$ftp_handle = ftp_connect ($host)
    or die ("Could not connect to host '$host'.");

// Ensure that our ftp connection is closed 
register_shutdown_function (
    create_function ('', "ftp_quit ($ftp_handle);")
);

$user = 'zak';
$pass = 'foo';
ftp_login ($ftp_handle, $user, $pass)
    or die ("Could not authenticate as user '$user'.");

// Grab a remote file and write it to the local file pointer 
$remote_file = 'remote_data.txt';
ftp_fget ($ftp_handle, $file_pointer, $remote_file, FTP_ASCII)
    or die ("Could not download remote file '$remote_file' using ftp_fget().");
?>

