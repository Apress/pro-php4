<html>
  <head></head>
    <body>
      <?php
      if (!($fileArray = file("a.txt"))) {
          printf("could not read a.txt file");
      }
      for ($i=0; $i < count($fileArray); $i++) {
          printf("%s<br>",$fileArray[$i]);
      }
      ?>
    </body>
</html>
