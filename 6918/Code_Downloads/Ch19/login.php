<?php

// Attempt to fetch the form variables
$username = $HTTP_POST_VARS['username'];
$password = $HTTP_POST_VARS['password'];
$dsn = "Library";

// If the username and password are valid,
// redirect the user to the search page.
if (isset($username) && isset($password)) {
    // Connect to the database
    $conn = odbc_connect($dsn, $username, $password) or die(odbc_error());

    // Query the database
    $sql = "SELECT username FROM users WHERE username = '" .
    $username . "' and password = '" . $password . "'";
    $result = odbc_exec($conn, $sql);

    // Test the query result
    $success = false;
    if (rtrim(odbc_result($result, 'username')) == $username) {
        $success = true;
    }

    // Close the connection to the database
    odbc_close($conn);
  
    // Redirect the user upon a success login
    if ($success) {
      header('Location: search.php');
    }
}
?>

<html>
  <head>
    <title>http://localhost/ProPHP4/Chapter17/login.php</title>
  </head>

  <body bgcolor="#ffffff" text="#000000">
    <h2>Online Library - Login</h2>

    <?php if (isset($success) && !$success): ?>
    <div style="color: #cc0000"><b>Login failure!</b></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      Username: <input name="username" type="text" /><br />
      Password: <input name="password" type="password" /><br />
      <input type="submit" value="Log in" />  
    </form>
  </body>
</html>

