<?php

//Target.php - file from which include() is called 
@include 'MyFile.inc';
if (defined("MY_INCLUDE_FILE")) {
    echo($myName);
} else {
    error_log("Could not include myFile.inc");
} 
?>
