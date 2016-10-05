<?php

// check for cookies with the algorithm:
// 1 – Send a cookie
// 2 – Reload the page
// 3 – Check to see if the cookie exists
// 4 – Perform desired action based on the results

if (!$cookie) {
    // Send a redirect header to the page proving that 
    // we’ve attempted to set the cookie.
    header("Location: $PHP_SELF?cookie=1");

    // Set a test cookie.
    setcookie("test", "1");
} else {
    // Test to see if the cookie hasn’t been set
    if (!$test) {
        // The cookie doesn’t exist
        echo ("Please enable cookies in your browser.");
    } else {
        // The cookie exists, send them to a page with cookie support.
        header("Location: http://yourserver.com/next.php");	
    }
}
?>
