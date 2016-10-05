<html>
  <head>
    <title>Online Library - Results</title>
  </head>

  <body bgcolor="#ffffff" text="#000000">
  <h2>Online Library - Results</h2>
  <table border="1" cellpadding="3" cellspacing="1">
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Price</th>
    </tr>

<?php
// Connect to the MySQL server
$conn = mysql_connect('localhost', 'root', 'hillary') or die(mysql_error());

// Select the database
mysql_select_db('Library', $conn) or die(mysql_error());

// Attempt to fetch the form variables
$query = addslashes($HTTP_GET_VARS['query']);
$series = $HTTP_GET_VARS['series'];
$type = $HTTP_GET_VARS['type'];

// Query the database for the list of series
$sql = 'SELECT book_title, auth_name, price ' .
    'FROM title, details, author, authortitle, series ' .
    'WHERE author.auth_ID = authortitle.auth_ID AND ' .
    'authortitle.ISBN = title.ISBN AND title.ISBN = details.ISBN ' .
    'AND details.series_ID = series.series_ID';

// Add the search terms to the query
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

$result = mysql_query($sql, $conn);

// Print the <option> rows for the <select> widget
if ($result && (mysql_num_rows($result) > 0)) {
    while ($row = mysql_fetch_assoc($result)) {
?>

  <tr>
    <td><u><?php echo htmlspecialchars($row['book_title']); ?></u></td>
    <td><?php echo htmlspecialchars($row['auth_name']); ?></td>
    <td>$<?php echo htmlspecialchars($row['price']); ?></td>
  </tr>

<?php
}
} else {
    echo ("<tr><td colspan=\"3\">No matches were found.</td></tr>\n");
}

// Close the database connection
mysql_close($conn);
?>

</table>
  <a href="search.php">Search Again</a>

  </body>
</html>

