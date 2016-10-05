<?php

$pages = array(1 => “main.html”, 2 => ”news.html”);
if(($index < 1) or ($index > 2))
    $index = 1;
?>

<html>
  <head>
    <title>My site</title.>
  </head>
  <body>
    <B>Welcome to my site</b><br />
    <?php include $pages[$index]; ?>
  </body>
</html>
