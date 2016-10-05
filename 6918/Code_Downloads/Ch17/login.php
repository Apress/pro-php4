<?php

// Attempt to fetch the form variables
$username = $HTTP_POST_VARS['username'];
$password = $HTTP_POST_VARS['password'];

// If the username and password are valid,
// redirect the user to the search page.
if (isset($username) && isset($password)) {

    // Connect to the database
    $conn = mysql_connect('localhost', 'jon', 'secret')
         or die(mysql_error());
    mysql_select_db('Library', $conn) or die(mysql_error());

    // Query the database
    $sql = "SELECT username FROM users WHERE username = '" .
    $username . "' and password = '" . $password . "'";
    $result = mysql_query($sql, $conn);

    // Test the query result
    $success = false;
    if (@mysql_result($result, 0, 0) == $username) {
        $success = true;
    }

    // Close the connection to the database
    mysql_close($conn);

    // Redirect the user upon a success login
    if ($success) {
        header('Location: search.php');
    }
}
?>

<html>
  <head>
    <title>Online Library - Login</title>
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

