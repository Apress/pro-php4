<?php

// (Connect to database ...)

$conn = mysql_connect("localhost", "root", "hillary")
      or die ("Could not connect to MySQL.");

$selected = mysql_select_db("Library", $conn)
        or die ("Could not select database.");

$sql = "SELECT ISBN, book_title FROM title";

$result = mysql_query($sql, $conn);

while ($row = mysql_fetch_object($result)) {
    echo("ISBN: " . htmlspecialchars($row->ISBN) . ", Title: " . htmlspecialchars($row->book_title) . "<br />";
}

mysql_free_result($result);
mysql_close($conn);
?>
