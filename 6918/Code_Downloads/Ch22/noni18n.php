<?php

function outNumFiles($count) 
{
    if ($count <= 0) {
        echo("No files.");
    } elseif ($count == 1) {
        echo("1 file.");
    } else {
        echo("$count files.");
    }
}

echo(outNumFiles(4));
echo("<BR>");

echo(outNumFiles(0));
?>
