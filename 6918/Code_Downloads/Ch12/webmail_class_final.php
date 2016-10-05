<?php

//webmail_class_final.php 

class webmail 
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
	
    var $del_mailbox = '';
    var $old_mailbox = '';
    var $new_mailbox = '';

    var $ERR_STR_CANT_DELETE_MESSAGE = "Can't delete the message!";
    var $ERR_STR_CANT_UNDELETE_MESSAGE = "Can't undelete the message!";
    var $ERR_STR_CANT_COPY_MESSAGE = "Can't copy the message!";
    var $ERR_STR_CANT_MOVE_MESSAGE = "Can't move the message!";
    var $ERR_STR_CANT_SET_FLAGS = "Can't set the flags!";
    var $ERR_STR_CANT_UNSET_FLAGS = "Can't unset the flags!";
	
    var $ERR_STR_CANT_CREATE_MAILBOX = "Can't create the mailbox!";
    var $ERR_STR_CANT_RENAME_MAILBOX = "Can't rename the mailbox!";
    var $ERR_STR_CANT_DELETE_MAILBOX = "Can't delete the mailbox!";	

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
	$this->mailbox = imap_utf7_encode($GLOBALS["mailbox"]);
	
	$this->del_mailbox = imap_utf7_encode($GLOBALS["del_mailbox"]);
	$this->old_mailbox = imap_utf7_encode($GLOBALS["old_mailbox"]);
	$this->new_mailbox = imap_utf7_encode($GLOBALS["new_mailbox"]);

	$this->msg_no = $GLOBALS["msg_no"];
	$this->msg_uid = $GLOBALS["msg_uid"];
	$this->filename = $GLOBALS["filename"];
	$this->part_no = $GLOBALS["part_no"];
	
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

    function start() 
    {
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
			
	    if(empty($msg->subject)) $arr[$i]["subject"] = $this->buildUrl("$read_action&msg_uid=$msg_uid&mailbox=$this->mailbox", $this->STR_NO_SUBJECT);
	    else {
	    $msg_subject = $this->decodeHeader($msg->subject);
	    $arr[$i]["subject"] = $this->buildUrl("$read_action&msg_uid=$msg_uid&mailbox=$this->mailbox", "$msg_prefix$msg_subject");
	    }
			
	    if(empty($msg->from)) $arr[$i]["from"] = $this->STR_NO_FROM;
	    else $arr[$i]["from"] = $this->make_address($msg->from, "$mail_action&msg_uid=$msg_uid&mailbox=$this->mailbox"); 
	}
        return $arr;
    }	

    function makeAddress($emails, $action) 
    {
        if(!is_array($emails)) return;
	foreach($emails as $email) {
            $personal = $this->decodeHeader($email->personal);
	    $address = $email->mailbox . "@" . $email->host;
	    if(!empty($personal)) $arr[] = $this->buildUrl("$action&email=$address", $personal); 
	    else $arr[] = $this->buildUrl("$action&email=$address", $address);
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

    function geMsg($download_action, $mail_action) 
    {

        if(!$this->msg_uid) {
	    $this->buildErrorMsg($this->ERR_STR_MSG_UID_INVALID, imap_last_error());
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
	} else if($struct->parts[0]->encoding == 4) {
	    $arr["body"] = imap_qprint(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
	    if(strtolower($struct->parts[0]->subtype) == 'html') $html = 1;
	} else {
	    if($struct->encoding == 3) {
	    $arr["body"] = imap_base64(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
	    if(strtolower($struct->subtype) == 'html') $html = 1;
	} else if($struct->encoding == 4) {
	    $arr["body"] = imap_qprint(imap_fetchbody($this->stream, $this->msg_uid, 1, FT_UID));
	    if(strtolower($struct->subtype) == 'html') $html = 1;
	} else { 
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
	        if(strtolower($attr->attribute) == 'name') {
	        $filename = $this->decode_header($attr->value);
	        break;
	    }

            $arr["parts"][$i] = $this->buildUrl("$download_action&mailbox=$this->mailbox&msg_uid=$this->msg_uid&part_no=$i&filename=$filename", $filename);
	    }
		
	    return $arr;
	}   

	function downloadAttachment() 
        {
	    $struct = @imap_fetchstructure($this->stream, $this->msg_uid, FT_UID);
	    if(!$struct) {
	        $this->buildErrorMsg($this->ERR_STR_MSG_UID_INVALID . $this->msg_UID, imap_last_error());
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
		
	    if($struct->parts[$this->part_no]->encoding == 3) // BASE64 Encoded
	        echo @imap_base64(imap_fetchbody($this->stream, $this->msg_uid, $this->part_no+1, FT_UID));

	    else if($struct->parts[$this->part_no]->encoding == 4)  // QUOTED_PRINTABLE
	        echo @imap_qprint(imap_fetchbody($this->stream, $this->msg_uid, $this->part_no+1, FT_UID));
		
            else echo @imap_fetchbody($this->stream, $this->msg_uid, $this->part_no+1, FT_UID);
		
		return true;
    }


    function getMailbox_list($ref='', $return_raw=0) 
    {
        if($this->protocol == 'pop3') {
	    if($return_raw) return $raw_mbox_array = array("\{$this->host}INBOX");
	    else {
	        $mbox_array['INBOX'] = 0;
		return $mbox_array;
	    }
	}
		
	else if($this->protocol =='nntp') $mailboxes = @imap_listmailbox($this->stream,"\{$this->host/$this->protocol:$this->port}", "*");
	else $mailboxes = @imap_listmailbox($this->stream,"\{$this->host}$ref", "*");
		
	if(!$mailboxes) { 
            $this->buildErrorMsg($this->ERR_STR_MAILBOX_NOT_AVAILABLE, imap_last_error());
	    return false;
	}
		
	foreach($mailboxes as $mbox) {
	    $mbox_name = imap_utf7_decode(eregi_replace("\{.*\}", "", $mbox));
		
	    $raw_mbox_array[] = $mbox_name;
		
	    if($this->protocol=='nntp') $status = @imap_status($this->stream, $mbox, SA_UNSEEN);
	    else $status = @imap_status($this->stream, $mbox, SA_UNSEEN);
	    if(!$status) {
	        $this->buildErrorMsg($this->ERR_STR_MAILBOX_STATUS_ERROR, imap_last_error());
	        return false;
	    }
		
	    $mbox_array[$mbox_name] = $status->unseen;
	}
		
	if($return_raw) return $raw_mbox_array;
	else return $mbox_array;
    }
	
    function createMailbox() 
    {
        if($this->protocol == 'nntp' || $this->protocol == 'pop3' || $this->new_mailbox == 'INBOX') {
	    $this->build_error_msg($this->ERR_STR_CANT_CREATE_MAILBOX, $this->new_mailbox);
	    return false;
	}
	if(!@imap_createmailbox($this->stream, "{\$this->host}$this->new_mailbox")) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_CREATE_MAILBOX, imap_last_error());
	    return false; 
	}
		
	return true;
    }
	
    function renameMailbox() 
    {
        if($this->protocol == 'nntp' || $this->protocol == 'pop3' || $this->new_mailbox == 'INBOX') {
	    $this->buildErrorMsg($this->ERR_STR_CANT_RENAME_MAILBOX, $this->new_mailbox);
	    return false;
	}
		
	if(!@imap_renamemailbox($this->stream, "{\$this->host}$this->old_mailbox", "{\$this->host}$this->new_mailbox")) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_RENAME_MAILBOX, imap_last_error());
	    return false; 
	}
		
	return true;
    }
	
    function deleteMailbox() 
    {
        if($this->protocol == 'nntp' || $this->protocol == 'pop3' || $this->del_mailbox == 'INBOX') {
	    $this->buildErrorMsg($this->ERR_STR_CANT_DELETE_MAILBOX, $this->del_mailbox);
	    return false;
	}
		
	if(!@imap_deletemailbox($this->stream, "{\$this->host}$this->del_mailbox")) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_DELETE_MAILBOX, imap_last_error());
	    return false;     
	}
		
	return true;
    }	

    function appendMail($mail_str, $mailbox) 
    {
        if(!@imap_append($this->stream, "{\$this->host}$mailbox", $mail_str)) {
	    $this->buildErrorMsg(imap_last_error());
	    return false;
	}
	else return true;
    	}
	
    function deleteMailMsg($msg_set) 
    {
        if(!@imap_setflag_full ($this->stream, $msg_set, "\\Deleted", ST_UID)) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_DELETE_MESSAGE, imap_last_error());
	    return false;
	}		
	return true;	
    }
	
    function undeleteMail_msg($msg_set) 
    {
        if(!@imap_clearflag_full ($this->stream, $msg_set, "\\Deleted", ST_UID)) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_UNDELETE_MESSAGE, imap_last_error());
	    return false;
	}		
	return true;	
    }
	
    function copyMailMsg($msg_set) 
    {	
        if(!@imap_mail_copy($this->stream, $msg_set, "{$this->server}$this->new_mailbox", CP_UID)) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_COPY_MESSAGE, imap_last_error());
	    return false;
	}	
	return true;
    }
	
    function moveMailMsg($msg_set) 
    {
        if(!@imap_mail_copy($this->stream, $msg_set, "{$this->server}$this->new_mailbox", CP_UID | CP_MOVE)) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_MOVE_MESSAGE, imap_last_error());
	    return false;
	}
	@imap_expunge($this->stream);
	return true;
    }
	
    function setMsgFlag($msg_set, $flags) 
    {
        if(!@imap_setflag_full($this->stream, $msg_set, $flags, ST_UID)) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_SET_FLAGS, imap_last_error());
	    return false;
	}
	return true;
    }
	
    function clearMsgFlag($msg_set, $flags) 
    {
        if(!@imap_clearflag_full($this->stream, $msg_set, $flags, ST_UID)) {
	    $this->buildErrorMsg($this->ERR_STR_CANT_UNSET_FLAGS, imap_last_error());
	    return false;
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