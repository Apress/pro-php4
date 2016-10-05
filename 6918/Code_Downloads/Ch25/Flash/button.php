<?php

function makeSquare($r, $g, $b)
{
    $s = new SWFShape();     
    $s->setRightFill($s->addFill($r, $g, $b));
    $s->movePenTo(-20,-20);
    $s->drawLineTo(20,-20);
    $s->drawLineTo(20,20);
    $s->drawLineTo(-20,20);
    $s->drawLineTo(-20,-20);
    return $s;
}  

$redShape = makeSquare(0xff, 0, 0);
$orangeShape = makeSquare(0xff, 0x90, 0);
$greenShape = makeSquare(0, 0xff, 0);
 
$button = new SWFButton();

$button->addShape($redShape, SWFBUTTON_UP | SWFBUTTON_HIT);
$button->addShape($orangeShape, SWFBUTTON_OVER);
$button->addShape($greenShape, SWFBUTTON_DOWN);

$button->addAction(new SWFAction("getURL('http://www.sitetronics.com/', 'newWindow');"), SWFBUTTON_DOWN);


$movie = new SWFMovie();
$movie->setDimension(320, 240);
$movie->setBackground(0xFF, 0xFF, 0xFF);
$movie->setRate(12.0);

$image = $movie->add($button);

$image->moveTo(100, 100);

header("Content-type: application/x-shockwave-flash");
$movie->output();
?>