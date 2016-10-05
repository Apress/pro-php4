<?php

if ($submit) {
    // set a cookie that expires on close
    setcookie("user_cookie", stripslashes($username));

    // The cookie isn’t accessable until the next page view...
    header("Location: $PHP_SELF");
}

if ($user_cookie) { 
    ?>
    <html> Welcome back,<strong>
    <?php echo stripslashes($user_cookie) ?></strong>!

<? } else { ?>
    <form method="post">
      Welcome, visitor. We strive to be as user-friendly as possible, so if you’ll please leave us your name, we’ll kindly greet you on your next visit!<p>
      Your name: <input type="text" name="username"><br><input type="submit" value="Send Me!" name=submit>
    </form>

<? } ?>
    </html>
