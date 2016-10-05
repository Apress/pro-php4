<?php

// webmail_class_test3.php

include("./webmail_class_ver3.php");

class My_Webmail extends Webmail 
{
    function start($action) 
    {
        switch($action) {
	case 'readMsg':
	    $msg = $this->readMsg();
            if(!$msg) return false;
	    echo $msg;
	    break;
		
	case 'downloadAttachment':
            if(!$this->download_attachment()) return 0;
	    break;
	    default: 
	    $msgs = $this->get_msg_list('action=read_msg','action=mail_form');
	    if(!$msg) return false;
	    foreach($msgs as $msg) echo $msg["subject"] . " - " . $msg["from"] . "<BR>";
	    break;
	}
    }
	
    function readMsg() 
    {
        $msg = $this->get_msg('action=download_attachment', 'action=mail_form&mode=reply');
		
	if(!$msg) return 0;
	$ret_str = "<STRONG>From: </STRONG>" . $msg["from"] . "<BR>\n";
	if(!empty($msg["cc"])) $ret_str .= "<STRONG>Cc: </STRONG>" . $msg["cc"] . "<BR>\n";
	$ret_str .= "<STRONG>Subject: </STRONG>" . $msg["subject"] . "<BR>\n";
	$ret_str .= "<BR><BR>\n";
	
	$ret_str .= "<BLOCKQUOTE>" . $msg["body"] . "</BLOCKQUOTE><BR>\n";
		
	if($msg["num_parts"] > 0) {
	    $ret_str .= "<CENTER><HR WIDTH=\"90%\" SIZE=\"1\"></CENTER>\n";
	for($i = 0; $i < count($msg["parts"]); $i++) $ret_str .= $msg["parts"][$i] . "<BR>\n";
    }
    return $ret_str;   
    }
   
}

$host = "mail.whatever.com"; 
$protocol = "imap";  
$port = 143;         
$userid = "wankyu";  
$userpassword = "12345"; 

$wmail = new My_Webmail();

if(!$wmail->init($host, $protocol, $port, $userid, $userpassword)) echo $wmail->error_msg();

if(!$wmail->start($action)) echo $wmail->errorMsg();

$wmail->end();
?>
