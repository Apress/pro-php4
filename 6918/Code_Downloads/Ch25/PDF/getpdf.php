<?php

$len = filesize("test.pdf");
header("Content-type: application/pdf");
header("Content-Length: $len");
header("Content-Disposition: inline; filename=foo.pdf");
readfile("test.pdf");
?>
