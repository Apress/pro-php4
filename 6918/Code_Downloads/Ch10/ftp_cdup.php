<?php

/* Connect to ftp server and authenticate */
$host = 'ftp.example.com';
$ftp_handle = ftp_connect ($host)   
    or die ("Could not connect to host '$host'.");

/* Ensure that our ftp connection is closed */
register_shutdown_function (create_function ('', "ftp_quit ($ftp_handle);"));

// Store the name of the current working directory (CWD)
$cwd = ftp_pwd ($ftp_link);

// Change the CWD to the parent of the CWD
ftp_cdup ($ftp_link)
    or die ("Could not set the Current Working Directory to the parent"  
        . " of the CWD (CWD is currently '$cwd')");

$new_cwd = ftp_pwd ($ftp_link);

// Close the connection
ftp_quit ($ftp_link);
?>
