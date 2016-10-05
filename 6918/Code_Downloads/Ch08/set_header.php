<?php

// Set the page to expire some date in the past
header ("Expires: Mon, 10 Oct 1983 18:59:00 GMT");

// Set the page to "always modified"
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 

// Send an HTTP/1.1 compliant cache-control message for new 
// browsers
header ("Cache-Control: no-cache, must-revalidate");

// Send an HTTP/1.0 compliant cache-control message for old 
// browsers
header ("Pragma: no-cache");
?>
