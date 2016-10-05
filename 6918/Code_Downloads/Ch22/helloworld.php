<?php

putenv("LC_ALL=da");
bindtextdomain("helloworld", "./locale");
textdomain("helloworld");
print(_("Hello World!"));
?>

