<?php

//webmail_class_ver1.php 

class Webmail 
{
    var $host = '';
    var $protocol = 'imap';
    var $supported_protocols = array('imap', 'pop3', 'nntp');
    var $port = 143;
    var $userid = '';
    var $userpassword = '';
    var $stream = 0;
    var $mailbox = '';
    var $auto_expunge = true;
	
    var $ERR_ARGS_DELIMITER = " ";
    var $ERROR_MSG = '';
	
    var $ERR_STR_CONNECTION_FAILED = 'Connection failed!';
    var $ERR_STR_PROTOCOL_NOT_SUPPORTED = 'Protocol not supported!';
    var $ERR_STR_CLOSE_FAILED = 'Error closing the stream!';
    var $ERR_STR_MAILBOX_NOT_AVAILABLE = 'Mailbox not available!';
    var $ERR_STR_OVERRIDE_START = "Override start() method!";

    function init($host, $protocol='imap', $port=143, $userid='', $userpassword='') 
    {
        $this->host = $host;
	$this->protocol = $protocol;
	$this->port = $port;
	$this->userid = $userid;
	$this->userpassword = $userpassword;
	$this->mailbox = $GLOBALS["mailbox"];
		
	if(!in_array($this->protocol, $this->supported_protocols)) {
	    $this->build_error_msg($this->ERR_STR_PROTOCOL_NOT_SUPPORTED, $this->protocol);
	    return false;
	}
		
	if($this->protocol == 'nntp' && empty($this->mailbox)) 
            $mode = OP_HALFOPEN;
	else $mode = false;
	    $this->stream = @imap_open("\{$this->host/$this->protocol:$this->port}$this->mailbox", $this->userid, $this->userpassword, $mode);
		
	if(!$this->stream) {
	    $this->build_error_msg($this->ERR_STR_CONNECTION_FAILED, imap_last_error());
	    return false;
	}
	return true;
    }

    function start() 
    {
        $this->build_error_msg($this->ERR_STR_OVERRIDE_START);
	return false;
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

    function buildErrorMsg($err_msg, $err_arg='') 
    {
        $this->ERROR_MSG = $err_msg . $this->ERR_ARGS_DELIMITER . $err_arg;
    }
	
    function errorMsg() 
    {
        return $this->ERROR_MSG;
    }
}
?>