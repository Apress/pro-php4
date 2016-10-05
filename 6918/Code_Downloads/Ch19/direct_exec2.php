<?php

//require("db_env.php");

$dsn = "Northwind"; // this is a valid DSN in odbc.ini, tested in odbctest
$user = "sa"; // a UserName in the DSN will override this.
$password = ""; //a Password in the DSN will override this.
$table = "Orders"; //table in Northwind schema
 
$sql = "SELECT * FROM $table WHERE OrderID = ?";

if ($conn_id = odbc_connect($dsn, $user, $password)) {
    echo("connected to DSN: $dsn <br>");

    if ($result = odbc_prepare($conn_id, $sql)) echo("Statement prepared<br>"); 

    $bound_param = array(10248, 10249);

    if (odbc_execute($result, $bound_param)) {
        echo("executing &nbsp; $sql<br>");
        if ($num_fields = odbc_num_fields($result) > 0) {
            odbc_result_all($result);
        } else {
            echo("no fields returned.");
            odbc_error();
        }
    }
    echo("closing connection $conn_id <br>");
    odbc_close($conn_id);
} else {
    echo("cannot connect to DSN: $dsn ");
}
?>
