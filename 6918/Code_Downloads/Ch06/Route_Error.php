<?php

//Route_Error.php
function logContentError($msg) 
{
    error_log($msg, 0);
    error_log($msg, 1, "content.manager@foowidgets.com", "Reply-To: content.manager@foowidgets.com"); 
}

function logDBError($msg) 
{
    error_log($msg, 0);
    error_log($msg, 1, "content.manager@foowidgets.com", "Reply-To: content.manager@foowidgets.com"); 
    error_log($msg, 3, "/tmp/dberrors.log");
}

?>
