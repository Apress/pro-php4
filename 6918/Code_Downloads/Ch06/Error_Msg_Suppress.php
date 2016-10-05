<?php

//Error_Msg_Suppress.php
$verbose = 1; //determines if error reporting should be verbose or succinct
$default_text = "A default line of text";

//Attempt to open a file and read a line of text from it
if ($file = @fopen("nosuchfile.txt", "r")) { 
    $text = (fgets($file, 101));

} elseif ($verbose) { 
    // if we have turned on verbose error reporting
    myLog("Failed to open nosuchfile.txt");
    echo $php_errormsg;
    //corrective action is to use an alternative line of text
    $text = $default_text;

} else { 
    // error reporting turned off
    myLog("Failed to open nosuchfile.txt");
    $text = $default_text; 
}

echo("Text read: " . $text);

//Simplified version of an error logging function
function myLog($msg) 
{
    echo("<H2>" . $msg . "</H2>");
}

?>
