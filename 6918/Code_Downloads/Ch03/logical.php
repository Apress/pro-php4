<?php

if (file_exists("travel.xml") && is_readable("travel.xml")) {
    fopen("travel.xml", r);
    echo("travel.xml opened");
} else {
    echo("travel.xml not opened");
}

?>