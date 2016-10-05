<?php

// Assume that we have already connected and authenticated to an FTP server 

$pasv_setting       = TRUE;
$pasv_setting_name  = $pasv_setting ? 'enabled' : 'disabled';

if (ftp_pasv ($ftp_handle, $pasv_setting)) {
    echo("PASV was successfully $pasv_setting_name.");
} else {
    echo("PASV could not be $pasv_setting_name.");
}
?>
