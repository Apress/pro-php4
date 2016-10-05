<html>
  <head>
    <title>
      Upload File Example
    </title>
  </head>
  <body>
    <?php
    printf("<b>Uploaded File Details</b><br><br>");
    printf("Name: %s <br>", $HTTP_POST_FILES["userfile"]["name"]);
    printf("Temporary Name: %s <br>",
                  $HTTP_POST_FILES["userfile"]["tmp_name"]);
    printf("Size: %s <br>", $HTTP_POST_FILES["userfile"]["size"]);
    printf("Type: %s <br> <br>", $HTTP_POST_FILES["userfile"]["type"]);

    if (copy($HTTP_POST_FILES["userfile"]["tmp_name"],"c:/temp/".$HTTP_POST_FILES["userfile"]["name"])) {
        printf("<b>File successfully copied</b>");
    } else {
        printf("<b>Error: failed to copy file</b>");
    }
    ?>
  </body>
</html>
