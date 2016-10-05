<?php

// empdir_first.php

require("empdir_functions.php");

if (!isset($choice)) {
    generateHTMLHeader("Click below to access the Directory");
    generateFrontPage();
} else if (strstr($choice, "ADD")) {
    $firstCallToAdd = 1;
    require('empdir_add.php');	
} else {
    $firstCallToSearch = 1;		
    require("empdir_search.php");	
}
?>