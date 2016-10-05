<?php

// empdir_search.php
include("empdir_common.php")

$searchFilter = "";

if (isset($firstCallToSearch)) {
    generateHTMLHeader("Search using the following criteria:");

    generateHTMLForm(0, "empdir_search.php", "SEARCH");

} else {	
    require("empdir_functions.php");

    if (!$cn && !$sn && !$mail && !$employeenumber && !$ou && !$telephonenumber) {
        generateHTMLHeader("Search using the following criteria:");
	displayErrMsg("Atleast one of the fields must be filled !!");

	generateHTMLForm(0, "empdir_search.php", "SEARCH");

    } else {
        $searchCriteria["cn"]= $cn;
	$searchCriteria["sn"]              = $sn;

	$searchCriteria["mail"]            = $mail;

	$searchCriteria["employeenumber"]  = $employeenumber;

	$searchCriteria["ou"]     	   = $ou;

	$searchCriteria["telephonenumber"] = $telephonenumber;


	$searchFilter = CreateSearchFilter($searchCriteria);

	$linkIdentifier = ConnectBindServer();
	if ($linkIdentifier) {
            $resultEntries = SearchDirectory($linkIdentifier, $searchFilter);
            if ($resultEntries) {
                generateHTMLHeader("Search Results:");

		printResults($resultEntries);

		returnToMain();
            } else {
          	ReturnToMain();

	    }
        } else {
        displayErrMsg("Connection to LDAP server failed !!");

	closeConnection($linkIdentifier);

	exit;
        }
    }
}
?>