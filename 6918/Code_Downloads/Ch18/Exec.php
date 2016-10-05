<?php

//Exec.php
$conn = pg_connect("dbname=library user=postgres");
$result = pg_exec($conn, "SELECT * FROM title");
pg_close($conn);
?>
