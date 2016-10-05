<?php

//Let the browser know an image is coming its way
header("Content-type: image/png");

//Create a new image with width x height of 250 x 250
$image = ImageCreate(250, 250);

//Define colors to be used by the image
//Red has a RGB Value of 255, 0, 0
$red = ImageColorAllocate($image, 255, 0, 0);

//This blue is just a full channel of blue
$blue = ImageColorAllocate($image, 0, 0, 255);

//We can now draw the line in the image from (0, 125) to
//(250, 125)
ImageLine($image, 0, 125, 250, 125, $blue);

//Send the image out to the world...
ImagePng($image);

//Free our memory resources
ImageDestroy($image);
?>
