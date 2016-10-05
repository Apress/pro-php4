<?php

//Fetch_Object.php

$conn = pg_connect("dbname=library user=postgres") or
        die(pg_errormessage());
$sql = "SELECT ISBN, book_title FROM title";
$result = pg_exec($conn, $sql);
$row_counter = 0;

while ($row = @pg_fetch_object($result, $row_counter)) {
    echo("ISBN:  " . htmlspecialchars($row->ISBN) . ",  Title:  " . htmlspecialchars($row->book_title) . "<br>");
    $row_counter++;
}

pg_freeresult($result);
pg_close($conn);
?>
