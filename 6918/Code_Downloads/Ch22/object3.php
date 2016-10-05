<?php

include("app1.php");

$obj = new App("da");

echo($obj->output->outNumFiles(2));

$obj->setLanguage('en');

echo("<BR>");

echo($obj->output->outNumFiles(2));
?>
