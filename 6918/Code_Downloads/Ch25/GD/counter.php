<?php

function hitCount($fileName) 
{
    //First we should see if we can open the file
    //to manipulate the counter
    if (!$filePointer = fopen($fileName, "r+")) {
        echo("Error opening file $fileName\n");
        exit;
    }

    //Next, we get the number of hits from the log file
    if (!$hits = fread($filePointer, filesize($fileName))) {
        echo("Error reading hits from $fileName\n");
        exit;
    }

    //Increment the number of hits
    $hits++;

    //Rewind the file for a clean write
    if (rewind($filePointer) == 0) {
        echo("Couldn't rewind file");
        exit;
    }

    //Write the updated number of hits to the file, if that file is 
    //not being written to already.  
    //We use flock to make sure that no other processes are 
    //using the file at the same time, ensuring accuracy.
    if (flock($filePointer, 2)) {
        if (!fwrite($filePointer, $hits, strlen($hits))) {
            echo("Couldn't write updated hits to $fileName");
            exit;
        }
    }

    flock($filePointer, 3);

    //Generate the Image
    $image = makeImage($hits);

    //Create <img> tag for calling page, and make it friendly 
    //for non-graphical browsers
    $counter = "<img src=\"$image\" alt=\"Hits: $hits\">";

    return $counter;
}

function makeImage($number) 
{
    $image = "./hits.png";

    //Set some variables for determining length and width
    //properties of our dynamic image
    $lenHits = strlen($number);
    $charHeight = ImageFontHeight(5);
    $charWidth = ImageFontWidth(5);
    $stringWidth = $charWidth * $lenHits;

    //Let's add some padding to our image dimensions, so the
    //image doesn't look too packed.
    $imgWidth = $stringWidth + 10;
    $imgHeight = $charHeight + 10;

    //Find centering dimensions
    $imgMidX = $imgWidth / 2;
    $imgMidY = $imgHeight / 2;

    //Create our image with identifier $i
    $i = ImageCreate($imgWidth, $imgHeight);

    //Set some standard color names for our image - since
    //$white is specified first, it becomes our image's
    //background color.  Black is the color of the text and
    //color of the drop shadow we will create
    $white = ImageColorAllocate($i, 255, 255, 255);
    $red = ImageColorAllocate($i, 255, 0, 0);
    $black = ImageColorAllocate($i, 0, 0, 0);

    //We'll make a "drop shadow" effect with two rectangles
    ImageFilledRectangle($i, 3, 3, $imgWidth, $imgHeight, $black);
    ImageFilledRectangle($i, 0, 0, $imgWidth-3, $imgHeight-3, $red);

    //Now we use the midpoint positions to locate the
    //area in which we will start drawing
    $textX = $imgMidX - ($stringWidth / 2) + 1;
    $textY = $imgMidY - ($charHeight / 2);

    //Draw the number
    ImageString($i, 4, $textX, $textY, $number, $black);

    //Output the image to a PNG file
    ImagePng($i, $image);

    //Return the path for the <IMG> tag
    return $image;
}
?>



