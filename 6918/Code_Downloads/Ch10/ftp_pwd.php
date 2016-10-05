<?php

// Assume that we have already connected and authenticated to an FTP server 

echo("The current remote working directory is " . ftp_pwd ($ftp_handle));
?>
