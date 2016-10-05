<?php

// Assume that we have already connected and authenticated to an FTP server 
$remote_file = 'remote_data.txt';

$mdtm = ftp_mdtm ($ftp_handle, $remote_file)
    or die ("Could not get the last modification time of remote file '$remote_file'.");

$date_and_time = date ('Y-m-d H:i:s', $mdtm);

echo("File '$remote_file' was last modified on $date_and_time");
?>
