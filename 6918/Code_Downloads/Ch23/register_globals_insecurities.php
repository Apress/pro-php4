<?php

if(isset($user)) {
    if($user == "admin") {	
        if($pass == "password") {
            $loggedin = 1;
        }
    }
}

if($loggedin == 1) {
    include "secretpage.html";
        exit;
}
?>

<html>
  <head>
    <title>Login</title>
  </head>
  <body>
    <form method="get" action="<?php echo $PHP_SELF ?>">
     <input type="test" name="user">
     <input type="password" name="pass">
     <input type="submit" value="Login">
    </form>
  </body>
</html>
