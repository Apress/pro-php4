<?php

//Result.php

$conn = pg_connect("dbname=library user=postgres") or
        die(pg_errormessage());
$sql = "SELECT book_title FROM title";
$result = pg_exec($conn, $sql);

if ($result) {
    $title = pg_result($result, 0, 'book_title');
    echo ("The title of the first book is $title.");
} else {
    echo ("Query failed:  $sql");
}
pg_close($conn);
?>
