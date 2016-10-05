<?php

$host = 'ftp.example.com';
$ftp_handle = ftp_connect ($host)
    or die("Could not connect to host '$host' on the default port.");
?>
