<?php

//MyStack1.php
require("./Stack1.php");

$myStack = new Stack();

echo("<h2>myStack operations</h2>");

echo("Popping before a push <br>");
$ret = $myStack->pop();
echo("Popped value: $ret <br><br>");

echo("Peeking before a push <br>");
$ret = $myStack->peek();
echo("Peeking: $ret <br><br>");

echo("Pushing 3 values into the stack<br><br>");
for ($i = 1; $i <= 3; ++$i ) {
    $myStack->push( $i );
}

echo("Peeking at the first value: ");
$ret = $myStack->peek();
echo "$ret <br><br>";

echo("Popping values now<br>");
for ($i = 1; $i <= 3; ++$i ) {
    $ret = $myStack->pop();
    echo("Popped value: $ret <br>");
}

$myStack->reset();

?>
