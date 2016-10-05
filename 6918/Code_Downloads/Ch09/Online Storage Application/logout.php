<?php

include("common.php");
if (!isSessionAuthenticated()) {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}

session_destroy();
?>

<html>
  <head>
    <title> Online Storage Application </title>
  </head>
  <body>
    <h1> Thanks <i><?php echo $username ?></i> for using Online Storage Application </h1>
  </body>
</html>
