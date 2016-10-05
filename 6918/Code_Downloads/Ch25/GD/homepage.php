<html>
  <head>
    <title>Test Page For Our Counter</title>
  </head>
  <body>

    <!-- HTML Content of The Page -->

    <?php
    include("counter.php");
    echo("We've had " . hitCount("hitlog.txt") . "visitors!");
    ?>

    <br>

    <!-- More content, if desired -->

  </body>
</html>
