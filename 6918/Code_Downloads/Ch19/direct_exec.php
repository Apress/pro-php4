<?php

//require("db_env.php");

$dsn = "Northwind"; // this is a valid DSN in odbc.ini, tested in odbctest
$user = "sa"; // a UserName in the DSN will override this.
$password = ""; //a Password in the DSN will override this.
$table = "Orders"; //a standard table in the Northwind schema
 
$sql = "SELECT * FROM $table";

if ($conn_id = odbc_connect($dsn, $user, $password)) {
    echo("connected to DSN: $dsn <br>");
    if ($result = odbc_exec($conn_id, $sql)) {
        echo("executing '$sql' <br>");
        echo("Results: ");
        odbc_result_all($result);
        echo("freeing result <br>");
        odbc_free_result($result);
    } else {
        echo("cannot execute '$sql' ");
        odbc_error();
    }
    echo("closing connection $conn_id <br>");
    odbc_close($conn_id);
} else {
    echo("cannot connect to DSN: $dsn ");
}
?>
