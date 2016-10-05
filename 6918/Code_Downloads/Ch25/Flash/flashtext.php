<?php

$movie = new SWFMovie();
$movie->setDimension(320, 240);
$movie->setBackground(0, 0x44, 0x95);
$movie->setRate(12.0);

$font = new SWFFont("Arial.fdb");

$string = new SWFText();
$string->setFont($font);
$string->setHeight(25);
$string->setColor(0, 0, 0);
$string->moveTo(10, 20);
$string->addString("PHP/Ming-Generated Text");

$movie->add($string);

header("Content-type: application/x-shockwave-flash");
$movie->output();
?>
