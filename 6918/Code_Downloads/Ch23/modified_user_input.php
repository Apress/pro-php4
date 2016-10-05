<?php
    
if(isset($_GET["filetype"]))
    exec("ls *.".escapeshellarg($_GET["filetype"]));
?>

<html>
  <body>
    <form method="get">
      Search Directory for files of type:
      <input type="text" name="filetype">
      <input type="submit">
    </form>
  </body>
</html>
