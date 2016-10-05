<html>
  <head>
    <title>Online Library - Search</title>
  </head>

  <body bgcolor="#ffffff" text="#000000">
    <h2>Online Library - Search</h2>
      <form action="results.php" method="GET">
        Query: <input name="query" type="text" /><br />
        Series: <select name="series">
        
        <?php
        // Connect to the MySQL server
        $conn = mysql_connect('localhost', 'jon', 'secret') or die(mysql_error());
        // Select the database
        mysql_select_db('Library', $conn) or die(mysql_error());
        // Query the database for the list of series
        $sql = "SELECT series_ID, book_series FROM series";
        $result = mysql_query($sql, $conn);
        // Print the <option> rows for the <select> widget
        if ($result && (mysql_num_rows($result) > 0)) {
            while ($row = mysql_fetch_assoc($result)) {
                $option = sprintf('<option value="%d">%s</option>',
                $row['series_ID'], $row['book_series']);
                echo("$option\n");
            }
        } else {
            echo("<option>No series are available</option>\n");
        }
        // Close the database connection
        mysql_close($conn);
        ?>

        </select><br />

        Type:
        <select name="type">
          <option value="isbn">ISBN</option>
          <option value="author">Author</option>
          <option value="title">Title</option>
        </select><br />
     <input type="submit" value="Search"/>
    </form>
  </body>
</html>
