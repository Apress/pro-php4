<html>
  Putting text or HTML tags here will force actual page content to be sent, causing the cookie to result in error.

  <?php
  $access++;
  setcookie("access", $access);
  ?>

  Thank you for visiting my site. You’ve seen this page 

  <?php 

  echo $access;
  if ($access == 1) {
      echo(" time!");
  } else {
      echo(" times!");
  }
  ?>

</html>
