<?php

// check to see if the user has submitted username data
if ($submit) { 
    // If first cookie does not exist, we will need to create it
    if (!$my_cookie[0]) {
        setcookie("my_cookie[0]", $username);
    }

    // Increment our counter cookie and set it.
    $my_cookie[1]++;
    setcookie("my_cookie[1]", $cookie[1]);

    // Use the ternary operator to give them the correct display
    // for how many times they have visited the page.
    echo ("Welcome back to my page, $my_cookie[0]! You've been here" .
    $my_cookie[1] . ($my_cookie[1] == 1 ? " time!" : " times!")); 

    } else { 
        ?>
        <form action=”<?=$PHP_SELF?>” method=”POST”>
        Username: <input type=”text” name=”username” /><br />
        <input type=”submit” name=”submit” value=”Log In”>
        </form>
        <?php    
    }
?>
 

