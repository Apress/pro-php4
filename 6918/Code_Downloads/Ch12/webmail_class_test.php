<?php

//webmail_class_test.php
include("./webmail_class_ver1.php");

class My_Webmail extends Webmail 
{
    function start() 
    {
        // Does nothing yet
	return true;
    }
}

$host = "mail.whatever.com"; 
$protocol = "imap";  
$port = 143; 
$userid = "wankyu";  
$userpassword = "12345"; 

$wmail = new my_webmail();

if(!$wmail->init($host, $protocol, $port, $userid, $userpassword)) 
    echo $wmail->error_msg();
else 
    echo ("Connected!");

if(!$wmail->start()) echo $wmail->errorMsg();

$wmail->end();
?>
