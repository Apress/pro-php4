<?php

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
?>

