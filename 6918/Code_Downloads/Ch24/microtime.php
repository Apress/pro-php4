<?php

$time_portions = explode(" ", microtime());
$actual_time = $time_portions[1].substr($time_portions[0],1);  
echo $actual_time;
?>
