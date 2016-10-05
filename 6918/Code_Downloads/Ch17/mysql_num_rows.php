<?php

$conn = mysql_connect("localhost", "root", "hillary")
      or die ("Could not connect to MySQL.");

$selected = mysql_select_db("Library", $conn)
        or die ("Could not select database.");

$sql = "SELECT book_title FROM title";
$result = mysql_query($sql, $conn);

if ($result) {
    $numRows = mysql_num_rows($result);
    echo("$numRows record(s) retrieved.");
} else {
    echo("Query failed: $sql");
}
mysql_close($conn);
?>
