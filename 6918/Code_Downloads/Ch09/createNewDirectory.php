<?php

if (mkdir("c:/temp/test", "0700")) {
    printf("New directory created");
} else {
    printf("Couldn't create directory");
}
?>
