<?php

// First generate a name for the data being accessed
$cache_name = md5($REQUEST_URI);
$time = date("U");

// Check if there's a valid entry in the cache
// The cache is valid for 10 minutes (600 seconds)

if (file_exists($cache_name) && ($time - filemtime($cache_name)) < 600) {
    $data=readfile($cache_name);
    echo $data;
} else {
    ob_start();
    // Regular code to generate content
    echo ("Hello world");
    $data = ob_get_contents();
    $fh = fopen($cache_name,'w+');
    fwrite($fh,$data);
    fclose($fh);
    ob_end_flush();
}
?>
