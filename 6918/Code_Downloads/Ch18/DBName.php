<?php

//DBName.php
$conn = pg_connect("dbname=library user=postgres");
echo("Current database:  " . pg_dbname());
pg_close($conn);
?>
