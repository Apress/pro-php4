<?php

//Connect.php
$conn = pg_connect("dbname=library user=postgres")
    or die("Could not connect to PostgreSQL.");
echo ("Connection successful.");
pg_close($conn);
?>
