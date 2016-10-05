<?php

// webmail_class_test2.php
include("./webmail_class_ver2.php");

class My_Webmail extends Webmail 
{
    function start() 
    {
        $msgs = $this->get_msg_list('read_msg','mail_form');
	if(!$msgs) return false;
	$ret_str = '';
	foreach($msgs as $msg) $ret_str .= $msg["subject"] . " - " . $msg["from"] . "<BR>";
	return $ret_str;
    }
}

$host = "news.php.net";
$protocol = "nntp";
$port = 119;
$userid = "";
$userpassword = "";
$mailbox = 'php.test';

$wmail = new My_Webmail();

if(!$wmail->init($host, $protocol, $port, $userid, $userpassword)) echo $wmail->errorMsg();

$list = $wmail->start();

if(!$list) {
    echo ($wmail->errorMsg());
} else {
    echo ($list);

$wmail->end();
?>
