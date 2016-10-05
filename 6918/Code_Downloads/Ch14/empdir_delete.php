<?php

//empdir_delete.php

include("empdir_common.php");
include("empdir_functions.php");

$dnString = "mail=" . urldecode($mail) . ",ou=" . urldecode($ou) . "," . $baseDN;

if (!isset($adminpassword)) {
    generateHTMLHeader("Administrator action:");
    promptPassword($mail, $ou, "empdir_delete.php");
    return;
}

$adminRDN = "cn=Admin," . $baseDN;

$linkIdentifier = connectBindServer($adminRDN, $adminpassword);

if ($linkIdentifier) {
    if (ldap_delete($linkIdentifier, $dnString) == true) {
        generateHTMLHeader("The entry was deleted succesfully");
	returnToMain();
	} else {
	    displayErrMsg("Deletion of entry failed !!");
	    closeConnection($linkIdentifier);
	    exit;
	}
    } else {
        displayErrMsg("Connection to LDAP server failed !!");
	exit;
    }
?>