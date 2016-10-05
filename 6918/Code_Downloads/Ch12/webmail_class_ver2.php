<?php

//webmail_class_ver2.php 

class webmail 
{
    var $sort = 'SORTDATE';
    var $reverse = 0;

    var $host = '';
    var $protocol = 'imap';
    var $supported_protocols = array('imap', 'pop3', 'nntp');
    var $port = 143;
    var $userid = '';
    var $userpassword = '';
    var $stream = 0;
    var $mailbox = '';
    var $auto_expunge = 1;

    var $ERR_STR_MAILBOX_STATUS_ERROR = 'Cannot get stat for the mailbox!';
    var $STR_NO_SUBJECT = 'NO SUBJECT';
    var $STR_NO_FROM = 'UNKNOWN';
    var $ERR_STR_CONNECTION_FAILED = 'Connection failed!';
    var $ERR_STR_PROTOCOL_NOT_SUPPORTED = 'Protocol not supported!';
    var $ERR_STR_CLOSE_FAILED = 'Error closing the stream!';
    var $ERR_STR_MAILBOX_NOT_AVAILABLE = 'Mailbox not available!';
    var $ERR_STR_OVERRIDE_START = "Override start() method!";	
    var $ERR_ARGS_DELIMITER = " ";
    var $ERROR_MSG = '';
	
    function init($host, $protocol='imap', $port=143, $userid='', $userpassword='') 
    {
	
        $this->host = $host;
        $this->protocol = $protocol;
        $this->port = $port;
        $this->userid = $userid;
        $this->userpassword = $userpassword;
        $this->mailbox = $GLOBALS["mailbox"];

        if(isset($GLOBALS["sort"])) $this->sort = $GLOBALS["sort"];
        if(isset($GLOBALS["reverse"])) $this->reverse = $GLOBALS["reverse"];
    
        if(!in_array($this->protocol, $this->supported_protocols)) {
            $this->buildErrorMsg($this->ERR_STR_PROTOCOL_NOT_SUPPORTED, $this->protocol);
            return false;
        }
		
        if($this->protocol == 'nntp' && empty($this->mailbox)) $mode = OP_HALFOPEN;
        else $mode = false;
        $this->stream = @imap_open("\{$this->host/$this->protocol:$this->port}$this->mailbox", $this->userid, $this->userpassword, $mode);
		
        if(!$this->stream) {
            $this->buildErrorMsg($this->ERR_STR_CONNECTION_FAILED, imap_last_error());
	    return false;
        }
        return true;
        }

      	function start() {
	    $this->buildErrorMsg($this->ERR_STR_OVERRIDE_START);
	    return false;
	}
    }
	
    function getMsgList($read_action, $mail_action) 
    {
        $msgs = @imap_sort($this->stream, $this->sort, $this->reverse, SE_NOPREFETCH);
	 	
	if(!is_array($msgs)) return false;
		
	for($i=0; $i < count($msgs); $i++) {
            $msg = @imap_header($this->stream, $msg_no);
	    $arr[$i]["no"] = $msg_no = $msgs[$i];
	    $arr[$i]["uid"] = $msg_uid = imap_uid($this->stream, $msg_no);
	
	    if($msg->Unseen == 'U' || $msg->Recent == 'R') $arr[$i]["unseen"] = true;
	    else $arr[$i]["unseen"] = false;
			
	    $arr[$i]["date"] = gmstrftime("%b %d %Y", strtotime($msg->date));
	
	    $struct = @imap_fetchstructure($this->stream, $msg_no);
	    $num_parts = count($struct->parts) - 1;
	    
            if($num_parts > 0) $msg_prefix = "@";
	    else $msg_prefix = '';
			
	    if(empty($msg->subject)) {
                $arr[$i]["subject"] = $this->buildUrl("$read_action&msg_uid=$msg_uid&mailbox=$this->mailbox", $this->STR_NO_SUBJECT);
	    else {
	        $msg_subject = $this->decodeHeader($msg->subject);
		$arr[$i]["subject"] = $this->buildUrl("$read_action&msg_uid=$msg_uid&mailbox=$this->mailbox", "$msg_prefix$msg_subject");
	    }
			
	    if(empty($msg->from)) 
                $arr[$i]["from"] = $this->STR_NO_FROM;
	    else 
                $arr[$i]["from"] = $this->make_address($msg->from, "$mail_action&msg_uid=$msg_uid&mailbox=$this->mailbox"); 
	    }
		
	    return $arr;
    }	

    function makeAddress($emails, $action) 
    {
        if(!is_array($emails)) return;
	foreach($emails as $email) {
            $personal = $this->decode_header($email->personal);
	    $address = $email->mailbox . "@" . $email->host;
	    if(!empty($personal)) $arr[] = $this->build_url("$action&email=$address", $personal); 
	    else $arr[] = $this->build_url("$action&email=$address", $address);
	}
	return implode(',', $arr);
    }

    function decodeHeader($arg) 
    {
        $dec_array = imap_mime_header_decode($arg);
		
	foreach($dec_array as $obj) $arr[]= $obj->text;
	if(count($arr) >0) return implode('', $arr);
	else return $arg;
    }
   
    function buildUrl($options, $link, $onclick='') 
    {
        global $PHP_SELF;
		
	if(!empty($onclick)) $onclick = " OnClick=\"$onclick\"";
		
	return "<A HREF=\"$PHP_SELF?$options\"$onclick>$link</A>" ;
    }

    function end() 
    {
        if($this->auto_expunge) $ret = @imap_close($this->stream, CL_EXPUNGE);
	else $ret = @imap_close($this->stream);
		
	if(!$ret) {
            $this->buildErrorMsg($this->ERR_STR_CLOSE_FAILED, imap_last_error());
	    return false;
	}
	return true;
    }

    function buildErrorMsg($err_msg, $err_arg='') {
        $this->ERROR_MSG = $err_msg . $this->ERR_ARGS_DELIMITER . $err_arg;
    }
	
    function errorMsg() 
    {
        return $this->ERROR_MSG;
    }
}
?>
