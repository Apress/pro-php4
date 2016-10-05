<?php

include("wrapper.php");
include("dansk.php");

function outNumFiles($count) 
{
    if ($count <= 0) {
        print(_("No files."));
    } elseif ($count == 1) {
        print(_("1 file."));
    } else {
        printf(_("%s files."), $count);
    }
}

echo(outNumFiles(2));

echo("<BR>");

echo(outNumFiles(0));
?>
