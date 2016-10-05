<?php

// empdir_modify.php

include("empdir_common.php");
include("empdir_functions.php");

if (isset($firstCall)) {
    $searchFilter = "(mail=*" . urldecode($mail) . "*)";
    $linkIdentifier = connectBindServer();
    if ($linkIdentifier) {
        $resultEntry = searchDirectory($linkIdentifier, $searchFilter);

    } else {
        displayErrMsg("Connection to LDAP server failed !!");	
    }
    generateHTMLHeader("Please modify fields: (e-mail & dept. cannot be changed)");
    generateHTMLForm($resultEntry, "empdir_modify.php", "MODIFY");
    closeConnection($linkIdentifier);
} else {
    $dnString = "mail=" . $mail . "," . "ou=". $ou . "," . $baseDN;
    $adminRDN = "cn=Admin," . $baseDN;
    $newEntry["cn"]              = $cn;
    $newEntry["sn"]              = $sn;
    $newEntry["employeenumber"]  = $employeenumber;
    $newEntry["telephonenumber"] = $telephonenumber;
    $linkIdentifier = connectBindServer($dnString, $userpassword);
    if ($linkIdentifier) {
        if ((ldap_modify($linkIdentifier, $dnString, $newEntry)) == false) {
            displayErrMsg("LDAP directory modification failed !!");
            closeConnection($linkIdentifier);
            exit;
		
        } else {
            generateHTMLHeader("The entry was modified succesfully");
	    returnToMain();
		
        }
	
    } else {
		
        displayErrMsg("Connection to LDAP server failed");
	exit;
    }
}
?>