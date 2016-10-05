<?php

class timer 
{
    var $timers = array();

    function timer()  
    {
        // Nothing
    }

    function timerStart($name = 'default') 
    {
        $time_portions = explode(' ',microtime());
        $actual_time = $time_portions[1].substr($time_portions[0],1);  
        $this->timers["$name"] = $actual_time; 
    }

    function timerStop($name = 'default')
    {
        $time_portions = explode(' ',microtime());
        $actual_time = $time_portions[1].substr($time_portions[0],1);  
        $elapsed_time = bcsub($actual_time, $this->timers["$name"], 6);
        return $elapsed_time;
    }
}
?>
