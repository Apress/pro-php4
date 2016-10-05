<?php

$conn = mysql_connect("localhost", "jon", "secret")
      or die("Could not connect to MySQL.");
$selected = mysql_select_db("Library", $conn)
        or die ("Could not select database.");
mysql_close($conn);
?>
