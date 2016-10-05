<?php
//Car.php
class Car 
{
    var $engine;
    var $requiredKey;
    // Constructor
    function Car() 
    {
        $this->requiredKey = new DefaultKey();
        $this->engine = new Engine();
    }

    function start($key) 
    {
        if ($key->equals($this->requiredKey)) {
            $this->engine->start();
            return true;
        }
    return false;
    } 

    function stop() 
    {
        if ($this->engine->isRunning()) {
            $this->engine->stop();
        }
    }

    // ... Several other methods such as moving and turning, and so on.
}

?>
