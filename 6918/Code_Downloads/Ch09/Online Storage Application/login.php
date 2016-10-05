<?php

include_once("common.php");

$username = trim($username);
$password = trim($password);

// Authenticate the User
if (($username == "") || ($password == "")) {
    sendErrorPage("The username and password you have entered is invalid. Please try again");
    exit;
}

$userProfileFile = $userProfileDir . $fileSeparator . $username;
	
// Check if the user profile file exists
if (!file_exists($userProfileFile)) {
    sendErrorPage("The username and password you have entered is invalid. Please try again");
    exit;
}

// Read the profile file
if (($fileContent = file($userProfileFile)) == null) {
    SendErrorPage("Internal Error: Could not read " . $userProfileFile . " file");
    exit;
}

// Get the username and password
for($i=0; $i < sizeof($fileContent); $i++) {
    $line = trim($fileContent[$i]);
    list ($name, $value) = split(":", $line);
    if ($name == "username") {
        $uName = $value;
    } else if ($name == "password") {
        $uPassword = $value;
    }
}

if (($uName != $username) || ($uPassword != crypt($password, CRYPT_STD_DES))) {
    sendErrorPage("The username and password you have entered is invalid. Please try again");
    exit;
}

// User authenticated
// Create a session for the User and set authenticated flag
if (!session_start()) {
    sendErrorPage("Internal Error: Could not create user session");
    exit;
}

if (!session_register("isAuthenticated")) {
    sendErrorPage("Internal Error: Could not add isAuthenticated variable to the user session");
    exit;
}

$isAuthenticated=true;

if (!session_register("username")) {
    sendErrorPage("Internal Error: Could not add username variable to the user session");
    exit;
}

if (!session_register("currentFolder")) {
    sendErrorPage("Internal Error: Could not add currentFolder variable to the user session");
    exit;
}

$currentFolder = $username;

include_once("main.php");
exit;
?>
