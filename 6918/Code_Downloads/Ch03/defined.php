<?php

define ("INDEPENDENCEDAY", "4th July");

if (defined("INDEPENDENCEDAY")) {
    echo("INDEPENDENCEDAY is defined");
} else {
    echo("INDEPENDENCEDAY is not defined");
}

echo("<br>");

echo("INDEPENDENCEDAY is " . (defined("INDEPENDENCEDAY") ? "defined" : "not defined"));

?>