<?php

//Numrows.php

$conn = pg_connect("dbname=library user=postgres") or
        die(pg_errormessage());
$sql = "SELECT book_title FROM title";
$result = pg_exec($conn, $sql);

if ($result) {
    $numRows = pg_numrows($result);
    echo("$numRows record(s) retrieved.");
} else {
    echo("Query failed:  $sql");
}
pg_close($conn);
?>
