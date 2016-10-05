<?php

//Stack.php
class Stack
{
    var $vector;
    var $stackPointer;

    function Stack()
    {
		$this->stackPointer = 0;
		$this->vector = array();
    }

    function isEmpty(){
        if ($this->stackPointer <= 0){
	    return 1;
	} else {
	    return 0;
	}
    }
		
    function push($element)
    {
        ++$this->stackPointer;
		$this->vector[$this->stackPointer] = $element;
    }

    function pop()
    {
        if ($this->isEmpty()){
	    return -1;
	} else {
	    $poppedValue = $this->vector[$this->stackPointer];
            --$this->stackPointer;
	    return $poppedValue;
	}
    }

    function peek()
    {
        if ($this->isEmpty()){
	    return -1;
	} else {
	    return $this->vector[$this->stackPointer];
	}
    }
	
    function reset()
    {
        $this->stackPointer = 0;
	$this->vector[$this->stackPointer] = -1;
    }
}
?>
