<?php 

//Log_Errors.php
//turning off error reporting so that error_log() takes over
error_reporting(0);
if (!fopen("fileAtLarge.txt", "r")) {
    // the error message is logged to the webserver’s error log
    error_log("File could not be opened", 0 ); 

    // the error message is logged as an e-mail
    error_log("File could not be opened", 1, "phpuser@php.wrox.com", "Reply-To:     phpcoder@somedomain.com");

    // send to debug port
    error_log("File could not be opened", 2,"debugmachine.somedomain.com:333");
	
    // log error message to a file
    error_log("File could not be opened", 3, "/var/adm/logs/php_errors.log");
}

?>
