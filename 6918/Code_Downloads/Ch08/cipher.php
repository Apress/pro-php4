<?php

//create initialization vector for the cipher
$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);

//key to decrypt the cipher
$key = “e46c7932ece519f2d0ce983614d5dfc4”;	

//text to encrypt
$cookietext = “dodell”;	$cipher = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,
 
//ciphertext
$cookietext, MCRYPT_MODE_ECB, $iv); 

//setcookie
setcookie(“username”, $cipher, mktime(0,0,0,05,10,2005), “/login.php”);
?>
