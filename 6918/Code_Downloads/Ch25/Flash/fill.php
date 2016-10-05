<?php

$movie = new SWFMovie();
$movie->setDimension(320, 240);
$movie->setBackground(0xFF, 0xFF, 0xFF);
$movie->setRate(12.0);

$shape = new SWFShape();
$fill = $shape->addFill(0xff, 0, 0);
$shape->setLeftFill($fill);
$shape->setLine(10, 0, 0xff, 0);

$shape->movePenTo(100, 100);

$shape->drawLine(0, -100);
$shape->drawLine(-100, 0);
$shape->drawLine(0, 100);
$shape->drawLine(100, 0);

$movie->add($shape);

header("Content-type: application/x-shockwave-flash");
$movie->output();
?>