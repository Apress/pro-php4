<?php

//Some German Special Characters 
$string = 'ä ü ö';

echo(ucwords($string)); // Gives you 'ä ü ö'

setlocale("LC_ALL", 'de_DE'); // here we are setting the German locale
echo(ucwords($string)); // Gives you 'Ä Ü Ö'
?>
