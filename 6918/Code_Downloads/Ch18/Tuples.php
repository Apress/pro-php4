<?php

//Tuples.php

$conn = pg_connect("dbname=library user=postgres") or
    die(pg_errormessage());
$sql = "UPDATE Details SET num_of_books=9 WHERE ISBN='1861003730'";
$result = pg_exec($conn, $sql);

if ($result) {
    $affectedRows = pg_cmdtuples($result);
    echo("$affectedRows record(s) updated.");
} else {
    echo("Query failed:  $sql");
}
pg_close($conn);
?>
