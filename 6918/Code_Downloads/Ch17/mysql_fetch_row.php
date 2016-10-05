<?php

// (Connect to database ...)

$conn = mysql_connect("localhost", "root", "hillary")
      or die ("Could not connect to MySQL.");

$selected = mysql_select_db("Library", $conn)
        or die ("Could not select database.");

$sql = "SELECT ISBN, book_title FROM title";

$result = mysql_query($sql, $conn);

while ($row = mysql_fetch_row($result)) {
    echo("ISBN: " . htmlspecialchars($row[0]) . ", Title: " . htmlspecialchars($row[1]) . "<br />";
}
mysql_free_result($result);
mysql_close($conn);
?>
