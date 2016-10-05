<?php

//webmail_class_ver3.php 

class WebMail 
{
    var $host = '';
    var $protocol = 'imap';
    var $supported_protocols = array('imap', 'pop3', 'nntp');
    var $port = 143;
    var $userid = '';
    var $userpassword = '';
    var $stream = 0;
    var $mailbox = '';
    var $auto_expunge = 1;

    var $sort = 'SORTDATE';
    var $reverse = 0;

    var $msg_no = 0;
    var $msg_uid = 0;
    var $part_no = 0;
    var $filename = '';
	
    var $ERR_STR_MSG_NO_INVALID = 'Invalid Message Number!';
    var $ERR_STR_MSG_UID_INVALID   = 'Invalid Message UID!';

    var $ERR_STR_MAILBOX_STATUS_ERROR = 'Cannot get stat for the mailbox!';
    var $STR_NO_SUBJECT = 'NO SUBJECT';
    var $STR_NO_FROM = 'UNKNOWN';
	
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

	$this->msg_no = $GLOBALS["msg_no"];
	$this->msg_uid = $GLOBALS["msg_uid"];
	$this->filename = $GLOBALS["filename"];
	$this->part_no = $GLOBALS["part_no"];

        if(isset($GLOBALS["sort"])) $this->sort = $GLOBALS["sort"];
        if(isset($GLOBALS["reverse"])) $this->reverse = $GLOBALS["reverse"];
	 	
		
	if(!in_array($this->protocol, $this->supported_protocols)) {
	    $this->build_error_msg($this->ERR_STR_PROTOCOL_NOT_SUPPORTED, $this->protocol);
	    return false;
	}
		
	if($this->protocol == 'nntp' && empty($this->mailbox)) $mode = OP_HALFOPEN;
	else $mode = false;
	$this->stream = @imap_open("\{$this->host/$this->protocol:$this->port}$this->mailbox", $this->userid, $this->userpassword, $mode);
		
	if(!$this->stream) {
	    $this->build_error_msg($this->ERR_STR_CONNECTION_FAILED, imap_last_error());
	    return false;
	}
	return true;
    }

    function start() {
        $this->buildErrorMsg($this->ERR_STR_OVERRIDE_START);
	return false;
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
			
	    if(empty($msg->subject)) $arr[$i]["subject"] = $this->build_url("$read_action&msg_uid=$msg_uid&mailbox=$this->mailbox", $this->STR_NO_SUBJECT);
	    else {
	        $msg_subject = $this->decode_header($msg->subject);
		$arr[$i]["subject"] = $this->build_url("$read_action&msg_uid=$msg_uid&mailbox=$this->mailbox", "$msg_prefix$msg_subject");
	    }
			
	    if(empty($msg->from)) $arr[$i]["from"] = $this->STR_NO_FROM;
	    else $arr[$i]["from"] = $this->make_address($msg->from, "$mail_action&msg_uid=$msg_uid&mailbox=$this->mailbox"); 
        }
		
	    return $arr;
    }	

    function makeAddress($emails, $action) 
    {
        if(!is_array($emails)) return;
	foreach($emails as $email) 
        {
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

    function getMsg($download_action, $mail_action) 
    {
        if(!$this->msg_uid) {
	    $this->build_error_msg($this->ERR_STR_MSG_UID_INVALID, imap_last_error());
	    return false;
	}
		
	$msg_no = imap_msgno($this->stream, $this->msg_uid);
		
	$headers = @imap_header($this->stream, $msg_no);
	if(!$headers) {
	    $this->buildErrorMsg($this->ERR_STR_MSG_NO_INVALID, imap_last_error());
	    return false;
	}
		
	$arr["date"] = gmstrftime ("%b %d %Y %H:%M:%S", strtotime($headers->date));
		
	$arr["raw_from"] = $this->decodeHeader($headers->fromaddress);
	$arr["raw_cc"] = $this->decodeHeader($headers->ccaddress);
	$arr["from"] = $this->makeAddress($headers->from, "$mail_action&msg_uid=$this->msg_uid&mailbox=$this->mailbox");
	$arr["cc"] = $this->makeAddress($headers->cc, "$mail_action&msg_uid=$this->msg_uid&mailbox=$this->mailbox");
	$arr["subject"] = $this->decodeHeader($headers->subject);
	
	if(empty($arr["subject"])) $arr["subject"] = $this->STR_NO_SUBJECT;
	$arr["message_id"] = $headers->message_id;
	$arr["references"] = $headers->references;
		
	$struct = @imap_fetchstructure($this->stream, $this->msg_uid, FT_UID);
		
	$arr["num_parts"] = count($struct->parts) - 1;
	$html = 0;
		
	if($struct->parts[0]->encoding == 3) {
	    $arr["body"] = imap_base64(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
	    if(strtolower($struct->parts[0]->subtype) == 'html') $html = 1;
	
	}else if($struct->parts[0]->encoding == 4) {
	
	    $arr["body"] = imap_qprint(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
	    if(strtolower($struct->parts[0]->subtype) == 'html') $html = 1;
	}
	else 
            if($struct->encoding == 3) {
	        $arr["body"] = imap_base64(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
		if(strtolower($struct->subtype) == 'html') $html = 1;
	    }
	else 
            if($struct->encoding == 4) {
	        $arr["body"] = imap_qprint(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
	        if(strtolower($struct->subtype) == 'html') $html = 1;
	    }
	    else {
	       $arr["body"] = imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID);
	       if(strtolower($struct->subtype) == 'html') $html = 1;		
	    }
        }
		
        if(!$html) {
            $arr["body"] = str_replace("\r\n", "<BR>", $arr["body"]);
            $arr["body"] = eregi_replace( "http://([-a-z0-9\_\./~@?=%(&amp;)|]+)", "<A HREF=\"http://\\1\">http://\\1</A>", $arr["body"]);
            $arr["body"] = eregi_replace( "ftp://([-a-z0-9\_\./~@?=%&amp;]+)", "<A HREF=\"ftp://\\1\">ftp://\\1</A>", $arr["body"]);
            $arr["body"] = eregi_replace( "([-a-z0-9\_\.]+)@([-a-z0-9\_\.]+)", "<A HREF=\"$PHP_SELF?$mail_action&email=\\1@\\2\">\\1@\\2</A>", $arr["body"]);
        }
    
        for($i=0; $i< count($struct->parts); $i++) {
            foreach($struct->parts[$i]->parameters as $attr) 
	        if($attr->attribute == 'NAME') {
	            $filename = $this->decode_header($attr->value);
	            break;
	        }
            $arr["parts"][$i] = $this->build_url("$download_action&mailbox=$this->mailbox&msg_uid=$this->msg_uid&part_no=$i&filename=$filename", $filename);
        }
        return $arr;
    }   

    function downloadAttachment() 
    {
        $struct = @imap_fetchstructure($this->stream, $this->msg_uid, FT_UID);
	if(!$struct) {
	    $this->build_error_msg($this->ERR_STR_MSG_UID_INVALID . $this->msg_UID, imap_last_error());
	    return false;
	}
		
	switch ($struct->parts[$this->part_no]->type) {
	case 0: $type = 'text';
	    break;
	case 1: $type = 'multipart';
	    break;
	case 2: $type = 'message';
	    break;
	case 3: $type = 'application';
	    break;
	case 4: $type = 'audio';
	    break;
	case 5: $type = 'image';
	    break;
	case 6: $type = 'video';
	    break;
	default: $type = 'other';
	    break;
	}
		
	$subtype = $struct->parts[$this->part_no]->subtype;
		
	header("Content-Type: $type/$subtype");
	header("Content-Disposition: ;filename=$this->filename");
		
	if($struct->parts[$this->part_no]->encoding == 3) { // BASE64 Encoded
            echo @imap_base64(imap_fetchbody($this->stream, $this->msg_uid, $this->part_no+1, FT_UID));
		
    	} else if($struct->parts[$this->part_no]->encoding == 4) {  // QUOTED_PRINTABLE
            echo @imap_qprint(imap_fetchbody($this->stream, $this->msg_uid, $this->part_no+1, FT_UID));
		
	} else 
            echo @imap_fetchbody($this->stream, $this->msg_uid, $this->part_no+1, FT_UID);
	}	
	return true;
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
            $this->build_error_msg($this->ERR_STR_CLOSE_FAILED, imap_last_error());
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