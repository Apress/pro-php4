<?php

// Assume we have connected to a server using ftp_connect and ftp_login $file = 'temp.txt';

ftp_delete ($ftp, $file)
    or die ("Could not delete file '$file'.");
?>
