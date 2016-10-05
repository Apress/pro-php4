<?php

$movie = new SWFMovie();
$movie->setDimension(320, 240);
$movie->setBackground(0xFF, 0xFF, 0xFF);
$movie->setRate(12.0);

$shape = new SWFShape();
$shape->setLine(20, 0, 0xff, 0);
$shape->drawLineTo(0, 100);
$shape->drawLineTo(100, 100);
$shape->drawLineTo(100, 0);
$shape->drawLineTo(0, 0);

$movie->add($shape);

header("Content-type: application/x-shockwave-flash");
$movie->output();
?>
