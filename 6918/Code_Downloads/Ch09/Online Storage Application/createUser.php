<?php

include("common.php");

// Check if the form variables are empty
$firstname = trim($firstname);
$lastname = trim($lastname);
$emailaddress = trim($emailaddress);
$username = trim($username);
$password = trim($password);
$confirmPassword = trim($confirmPassword);

if (($firstname == "") || ($lastname == "") || ($emailaddress == "") || ($username == "") || ($password == "") || ($confirmPassword == "")) {
    sendErrorPage("Error: Not all the form fields are filled  ");
    exit;
}

if ($password != $confirmPassword) {
    sendErrorPage("Error: password and confirm password values don't match"); 
    exit;
}

// Check if the user name already exists
$userProfileFile = $userProfileDir . $fileSeparator . $username;
if (file_exists($userProfileFile)) {
    sendErrorpage("Error: User Name " . $username . " already exists");
    exit;
} 

// Create user's profile file
if (($fp = fopen($userProfileFile, "w+")) < 0) {
    sendErrorPage("Internal Error: Could not create file " . $userProfileFile);
    exit;
}

fwrite($fp, "firstname:" . $firstname . "\n");
fwrite($fp, "lastname:" . $lastname . "\n");
fwrite($fp, "emailaddress:" . $emailaddress . "\n");
fwrite($fp, "username:" . $username . "\n");

// Currently storing the passwd in clear text, will use mhash later
fwrite($fp, "password:" . crypt($password, CRYPT_STD_DES) . "\n");
fclose($fp);

// Create users home directory
if (createFolder("/", $username) <= 0) {
    sendErrorPage("Internal Error: Could not create directory " . $username);
    exit;
}

// Create the mimeTypes file
$mimeTypeFile = $username . "/" . "mimeTypes";
if (!fopen(getAbsolutePath($mimeTypeFile), "w+")) {
    sendErrorPage("Internal Error: Could not create file " . $mimeTypeFile);
    exit;
}

?>

<html>
  <head>
  </head>
  <body>
    <h1> User <?php echo $username ?> Created. Go to the <a href="Login.html"> Login page </a></h1>
  </body>
</html>
