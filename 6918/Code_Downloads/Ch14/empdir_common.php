<?php

//empdir_common.php

// Avoid multiple include()
if (isset($EMPDIR_CMN)) {
    return;
} else {
    $EMPDIR_CMN = true;
}

//Customize these to your environment

$baseDN = "o=Foo Widgets, c=us";
$ldapServer = "www.foowid.com";
$ldapServerPort = 4321;
?> 