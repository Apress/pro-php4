<?php

$conn = mysql_connect("localhost", "root", "hillary")
      or die("Could not connect to MySQL.");

$selected = mysql_select_db("Library", $conn)
        or die ("Could not select database.");

$sql = "UPDATE details SET num_of_books=9 WHERE ISBN='1861003730'";

$result = mysql_query($sql, $conn);

if ($result) {
    $affectedRows = mysql_affected_rows($conn);
    echo("$affectedRows record(s) updated.");
} else {
    echo("Query failed: $sql");
}
mysql_close($conn);

?>
