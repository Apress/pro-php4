<html>
  <head>
    <title>http://localhost/ProPHP4/Chapter17/search.php</title>
  </head>

  <body bgcolor="#ffffff" text="#000000">
    <h2>Online Library - Search</h2>
    <form action="results.php" method="GET">
      Query: <input name="query" type="text" /><br />
      Series: <select name="series">

      <?php
      $dsn = "Library";

      // Connect to the MySQL server
      $conn = odbc_connect($dsn, $username, $password) or die(odbc_error());

      // Query the database for the list of series
      $sql = 'SELECT series_ID, book_series from series';
      $result = odbc_exec($conn, $sql);

      // Print the <option> rows for the <select> widget
      if ((odbc_num_rows($result) != 0)) {
          while ($row = odbc_fetch_row($result)) {
              $series_id = odbc_result($result, "series_ID");
              $book_series = odbc_result($result, "book_series");
              $option = sprintf("<option value=\"%d\">%s</option>",
              $series_id, $book_series);
              echo("$option\n");
           }
      } else {
          echo "<option>No series are available</option>\n";
      }

      // Close the database connection
      odbc_close($conn);
      ?>

    </select><br />
      Type:
      <select name="type">
        <option value="isbn">ISBN</option>
        <option value="author">Author</option>
        <option value="title">Title</option>
      </select><br />
      <input type="submit" value="Search"/input>
    </form>
  </body>
</html>




