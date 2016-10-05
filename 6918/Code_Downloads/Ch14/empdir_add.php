<?php

//empdir_add.php

if (isset($firstCallToAdd)) {
    generateHTMLHeader("Please fill in fields: (Name, Dept. and E-mail mandatory)");
    generateHTMLForm(0, "empdir_add.php", "ADD");
} else {
    require("empdir_common.php");
    require("empdir_functions.php");
    if (!$cn || !$mail || !$ou) {
        generateHTMLHeader("Please fill in fields: ");
	fisplayErrMsg("Minimally Name, Dept. and E-mail fields  are required!!");
	generateHTMLForm(0, "empdir_add.php", "ADD");
    } else {
	$entryToAdd["cn"] = $cn;
	$entryToAdd["sn"] = $sn;
	$entryToAdd["mail"] = $mail;
	$entryToAdd["employeenumber"] = $employeenumber;
	$entryToAdd["ou"] = $ou;
	$entryToAdd["telephonenumber"] = $telephonenumber;
	$entryToAdd["objectclass"] = "person";
	$entryToAdd["objectclass"] = "organizationalPerson";
	$entryToAdd["objectclass"] = "inetOrgPerson";
	$dnString = "mail=" . $mail . "," . "ou=". $ou . "," . $baseDN;
	$adminRDN = "cn=Admin," . $baseDN;
	$linkIdentifier = connectBindServer($adminRDN, $adminpassword);
	if ($linkIdentifier) {
	    if (ldap_add($linkIdentifier, $dnString, $entryToAdd) == true) {
	        generateHTMLHeader("The entry was added succesfully");
		returnToMain();
            } else {
	        displayErrMsg("Addition to directory failed !!");
                closeConnection($linkIdentifier);
	        returnToMain();
                exit;
	    }
	} else {
	    displayErrMsg("Connection to LDAP server failed!");
	    exit;
        }
    } 	
}	
?>