<?php

$passphrase="this is my secret passphrase";
echo ("My passphrase hashed using md5 is: ");
echo mhash(MASH_MD5, $passphrase);
?>
