<html>
  <head></head>
  <body>
  
  <?php
  function cleanTemporaryFiles($directory) 
  {
      $dir = opendir($directory);
      while (($file = readdir($dir))) {
          if (is_file($directory . "/" . $file)) {
              $accessTime = fileaTime($directory . "/" .$file);
              $time = time();
              
              if (($time - $accessTime) > 10*24*60*60) {
                  if (unlink($directory . "/" .$file)) {
                      printf("File %s is removed from %s directory <br>\n", $file, $directory);
                  }
              }
          } else if (is_dir($directory . "/" .$file) && ($file != ".") && ($file != "..")) {
              cleanTemporaryFiles($directory . "/" . $file);
          }
      }
      closedir($dir);
  }
  cleanTemporaryFiles("c:/temp");
  ?>

  </body>
</html>
