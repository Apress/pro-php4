<?php

// Assume that we have already connected and authenticated to an FTP server 

$systype = ftp_systype ($ftp_handle)
    or die ("The system type of the FTP server cannot be determined.");

echo("The FTP server's system type is '$systype'.");
?>

