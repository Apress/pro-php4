<?php

// This cookie will expire in half an hour from the current 
// time, as that's 1800 seconds and cookie functions relate to that
setcookie("my_cookie", $value, time() + 1800);

// This cookie will expire at midnight on May 10, 2005
setcookie("my_cookie", $value, mktime(0,0,0,05,10,2005));

// This cookie will expire at 6:59 PM on May 10, 2005
setcookie("my_cookie", $value, mktime(18,59,0,05,10,2005));
?>
