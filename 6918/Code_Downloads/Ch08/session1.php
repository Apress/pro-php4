<?php

session_start();                        // start or continue the session
$user = "dodell";                       // initialize a variable for the user

// register the "user" variable and give output.
if (session_register("user")) {
    echo("User field set to $user.");
} else {
    echo("Could not set the session variable!");
}
?>
