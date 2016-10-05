<?php

// Assume that we have already connected and authenticated to an FTP server 

// Get a list of the files in the remote current working directory 
$directory = '.';

$file_list = ftp_nlist ($ftp_handle, $directory)
    or die ("Could not list the files in directory '$directory'.");

echo("Directory '$directory' contains the following files:");
print_r ($file_list);
?>
