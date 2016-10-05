<?php

$conn = mysql_connect("localhost", "root", "hillary")
    or die("Could not connect to MySQL.");

$selected = mysql_select_db("Library", $conn)
      or die ("Could not select database.");

$result = mysql_query("SELECT * from author");

mysql_close($conn);
?>
