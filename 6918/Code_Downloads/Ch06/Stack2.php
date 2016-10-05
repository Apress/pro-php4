<?php

//Stack2.php
require("phpcodesite.php");
CS_SetEnabled(TRUE);

class Stack
{
    var $vector;
    var $stackPointer;

    function Stack()
    {
        CS_EnterMethod("Stack");
	CS_SendNote("Initializing Stack<br>");
	$this->stackPointer = 0;
	$this->vector[0] = -1;
	CS_ExitMethod("Stack");
    }

    function isEmpty()
    {
        CS_EnterMethod("isEmpty");
	if ($this->stackPointer <= 0){
	    CS_ExitMethod("isEmpty");
            return 1;
	} else {
	    CS_ExitMethod("isEmpty");
	    return 0;
	}
    }
		
    function push($element)
    {
        CS_EnterMethod("push");
	++$this->stackPointer;
	$this->vector[$this->stackPointer] = $element;
	CS_ExitMethod("push");
    }

    function pop()
    {
        CS_EnterMethod("pop");
	if ($this->isEmpty()){
	    CS_SendError("Stack empty<br>");
	    CS_ExitMethod("pop");
	    return -1;
	} else {
	    $ret = $this->vector[$this->stackPointer];
	    --$this->stackPointer;
	    CS_SendVar( "stackPointer", $this->stackPointer );
            CS_ExitMethod("pop");
	    return $ret;
	}
    }

    function peek()
    {
        CS_EnterMethod("peek");
	if ($this->isEmpty()){
	    CS_SendError("Stack empty<br>");
	    CS_ExitMethod("peek");
            return -1;
	} else {
            CS_ExitMethod("peek");
	    return $this->vector[$this->stackPointer];
	}
    }
	
    function reset()
    {
        CS_EnterMethod("reset");
	$this->stackPointer = 0;
	$this->vector[$this->stackPointer] = -1;
	CS_DisplayInputData();
	CS_ExitMethod("reset");
    }

    function pop()
    {
        if ($this->isEmpty()){
            echo("Stack empty<br>");
	    return -1;
	} else {
	    return $this->vector[--$this->stackPointer];
	}
    }

}
?>
