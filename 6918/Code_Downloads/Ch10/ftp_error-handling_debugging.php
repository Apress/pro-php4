<?php

// Put the host and port name into variables as a debugging aid
$host = 'ftp.wrox.com';
$port = 21;
$user = 'anonymous';
$pass = 'zak@fooassosciates.com';

// FTP servers may not respond very quickly. 
set_time_limit (120);
$ftp_link = ftp_connect ($host, $port)
    or die ("Could not connect to FTP server '$host' on port $port");

// Login to the connection
$login = ftp_login ($ftp_link, $user, $pass);

// If we managed to login successfully
if ($login) {
    // List all the files in the root directory
    $file_list = ftp_nlist ($ftp_link, "./beginning");

    // Download all the files in the root directory
    if (is_array ($file_list)) {
        foreach ($file_list as $file) {
            // Try to save each file in the local directory
            // under the same name as the remote file
            if (ftp_get ($ftp_link, $file, $file, FTP_BINARY)) {
                echo("File '$file' downloaded.<br>");
            } else {
                echo("Could not download file '$file'.<br>");
            }
        }
    } else {
    echo("No files to download.");
}

// Exit gracefully and display an error message if login failed
} else {
    echo("Could not login to '$host:$port' as user '$user' " ."(password hidden).<br>");
}

// Close the connection
ftp_quit ($ftp_link);
?>
