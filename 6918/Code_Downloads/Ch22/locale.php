<?php

//Some German Special Characters 
$string = '� � �';

echo(ucwords($string)); // Gives you '� � �'

setlocale("LC_ALL", 'de_DE'); // here we are setting the German locale
echo(ucwords($string)); // Gives you '� � �'
?>
