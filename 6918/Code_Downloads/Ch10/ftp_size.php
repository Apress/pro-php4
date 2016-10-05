<?php

$file = '/path/to/file.txt';

$size = ftp_size ($ftp_handle, $file);

if (-1 == $size) {
    echo("The size of file '$file' could not be determined.");
} else {
    echo("File '$file' is $size bytes in size.");
}
?>

