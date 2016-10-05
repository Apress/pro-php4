<?php

//Fetch_Assoc.php

$conn = pg_connect("dbname=library user=postgres") or
        die(pg_errormessage());
$sql = "SELECT ISBN, book_title FROM title";
$result = pg_exec($conn, $sql);
$row_counter = 0;

while ($row = @pg_fetch_array($result, $row_counter, PGSQL_ASSOC)) {
    echo("ISBN:  " . htmlspecialchars($row['isbn']) . ",  Title:  " . htmlspecialchars($row['book_title']) . "<br />");
    $row_counter++;
}

pg_freeresult($result);
pg_close($conn);
?>
