<?php

//Stack_Tester.php
require('./Stack2.php');
require('./phpunit/phpunit.php');

class Stack_Tester extends TestCase
{
    var $stack1;
    var $stack2;
    var $stack3;
    var $stack4;

    function Stack_Tester($method)
    {
        $this->TestCase($method);
    }

    function setUp()
    {
        $this->stack1 = new Stack();
	$this->stack2 = new Stack();
	$this->stack3 = new Stack();
	$this->stack4 = new Stack();
    }

    function testPush()
    {
        $this->stack1->push(27);
	$this->assertEquals(27, $this->stack1->peek(),
	"push() method failed test");
    }

    function testPop()
    {
        $this->stack2->push(108);
	$ret = $this->stack2->pop();
	$this->assertEquals(108, $ret,
	"pop() method failed");
    }

    function testPeek()
    {
        $this->stack3->push(1921);
	$ret = $this->stack3->peek();
	$ret2 = $this->stack3->peek();
	$this->assert( $ret == $ret2 && $ret2 == 1921 );
    }

    function testIsEmpty()
    {
        $this->stack4->push(1547);
	$this->stack4->pop();
	$ret = $this->stack4->isEmpty();
	$this->assert( $ret == 1 );
    }

    function tearDown()
    {
        echo("Finished running test......<br>");
    }
}
?>