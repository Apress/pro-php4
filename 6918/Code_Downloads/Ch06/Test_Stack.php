<?php

//Test_Stack.PHP
require("Stack_Tester.php");

$suite = new TestSuite();

$suite->addtest(new Stack_Tester("testPush"));
$suite->addtest(new Stack_Tester("testPop"));
$suite->addtest(new Stack_Tester("testPeek"));
$suite->addtest(new Stack_Tester("testIsEmpty"));

$testRes = new TextTestResult();

$suite->run(&$testRes);

$testRes->report();
?>
