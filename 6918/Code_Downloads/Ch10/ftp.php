<?php

// Connect to FTP server
$ftp_link = ftp_connect ('ftp.wrox.com');

// Log in with username and password
ftp_login ($ftp_link, 'anonymous', 'foo@bar.com');

// Fetch a file in ASCII mode
ftp_get ($ftp_link, '/noscan', 'noscan', FTP_ASCII);

// Close the FTP connection
ftp_quit ($ftp_link);
?>
