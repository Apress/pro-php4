<?php 

//Sample_Trace.php
include('Trace_Debugger.inc');

function swap(&$a, &$b)
{
    $a = $a + $b;
    $b = $a - $b;
    $a = $a - $b;
}

$a = 1234;
$b = 4567;
trace_debug(__FILE__, __LINE__, "a", $a);
echo("a = $a, b = $b");
swap($a, $b);
trace_debug(__FILE__, __LINE__, "a", $a);
echo("a = $a, b = $b");
?>
