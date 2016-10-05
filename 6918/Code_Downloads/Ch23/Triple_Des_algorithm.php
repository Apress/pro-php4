<?php

$key = "This is our secret key";
$string = "This is the string that we want to encrypt so no one else can read it!!";

//Encrypt our string
$encrypted_message = mcrypt_ecb(MCRYPT_3DES, $key, $string, MCRYPT_ENCRYPT);
?>
