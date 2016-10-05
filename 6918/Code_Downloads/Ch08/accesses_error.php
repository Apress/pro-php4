
Putting text here will force a header of Content-type: text/html to be sent, 
causing the cookie to result in error.

<?php

$accesses++;
setcookie("accesses", $accesses);
?>

<html> Thank you for visiting my site. You’ve seen this page 
  <?php 
  echo $accesses;
  if ($accesses == 1) {
      echo(" time!");
  } else {
      echo(" times!");
  }
  ?>
</html>
