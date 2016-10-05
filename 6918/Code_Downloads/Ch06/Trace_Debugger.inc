<?php

//Trace_Debugger.inc

//Configuration section -- BEGIN
define("TRACE_DEBUGGING", true);  // set this to ‘false’ to disable tracing
$debug_host = "myphpdebug.mydomain.com";  // set this to the machine where a snoop server is listening
$debug_port = 23456; // port at which the snoop server is listening
//Configuration section -- END

function trace_debug($fileName, $lineNumber, $varName, $varValue)
{
    if (TRACE_DEBUGGING) {
        $traceMessage = "Tracing $fileName at $lineNumber: $varName = $varValue \n";
        error_log($traceMessage, 2, "$debug_host:$debug_port");
    }
}
?>
