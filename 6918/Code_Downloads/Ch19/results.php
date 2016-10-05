<?php

//require("db_env.php"); // *nix specific environment variables

$dsn = "Library";

// Connect to the database
$conn = odbc_connect($dsn, $username, $password) or die(odbc_error());

// Attempt to fetch the form variables, passed in from browser.
$query = addslashes($HTTP_GET_VARS['query']);
$series = $HTTP_GET_VARS['series'];
$type = $HTTP_GET_VARS['type'];

// Query the database for the list of series
$sql = "SELECT title.book_title, author.auth_name, details.price " .
       "FROM title, details, author, authortitle, series " .
       "WHERE author.auth_ID = authortitle.auth_ID AND " .
       "authortitle.ISBN = title.ISBN AND title.ISBN = details.ISBN " .
       "AND details.series_ID = series.series_ID";

// Add the search terms to the query - building the where clause on the fly depending on type specified by user.
if (!empty($series)) {
    $sql .= " AND series.series_ID = $series";
}

if (!empty($query) && !empty($type)) {
    if ($type == 'isbn') {
        $sql .= " AND details.ISBN = '$query'";
    } elseif ($type == 'author') {
        $sql .= " AND author.auth_name LIKE '%$query%'";
    } elseif ($type == 'title') {
        $sql .= " AND title.book_title LIKE '%$query%'";
    }
}

$result = odbc_exec($conn, $sql) or die(odbc_error()); //execute the SQL

// Print the <option> rows for the <select> widget
if ((odbc_num_rows($result) != 0)) { //remember, this will still work for -1
    while ($row = odbc_fetch_row($result)) {  
        $book_title = odbc_result($row, "book_title");
        $auth_name = odbc_result($row, "auth_name");
        $price = odbc_result ($row, "price");
?>

<tr>
  <td><u><?php echo(htmlspecialchars($book_title)) ?></u></td>
  <td><?php echo(htmlspecialchars($auth_name)) ?></td>
  <td>$<?php echo(htmlspecialchars($price)) ?></td>
</tr>

<?php
    }
} else { //if odbc_num_rows is "0" then the above will be skipped
    echo("<tr><td colspan=\"3\">No matches were found.</td></tr>\n");
}

// Close the database connection - good housekeeping!
odbc_close($conn); 
?>

