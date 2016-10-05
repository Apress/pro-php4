<?php

//initialization vector
$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);

//decipher the ciphertext in the cookie
$valid_user = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, “e46c7932ece519f2d0ce983614d5dfc4”, $username, MCRYPT_MODE_ECB, $iv); 

//echo the plaintext
echo("Welcome Back $valid_user"); 

?>
