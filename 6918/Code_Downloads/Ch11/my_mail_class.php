<?php

// my_mail_class.php

class My_Mail 
{
    var $to = '';
    var $from = '';
    var $reply_to = '';
    var $cc = '';
    var $bcc = '';
    var $subject = '';
    var $body = '';
	
    var $validate_email = true;
    var $rigorous_email_check = false;

    var $allow_empty_subject = false;
    var $allow_empty_body = false;
	 
    var $headers = array();
	
    var $ERROR_MSG;
	
    var $ERR_EMPTY_MAIL_TO = "Empty to field!";
    var $ERR_EMPTY_SUBJECT = "Empty subject field!";
    var $ERR_EMPTY_BODY = "Empty body field!";
    var $ERR_SEND_MAIL_FAILURE = "An error occured while attempting to send email!";
    var $ERR_TO_FIELD_INVALID = "To field contains invalid email address(es)!";
    var $ERR_CC_FIELD_INVALID = "Cc field contains invalid email address(es)!";
    var $ERR_BCC_FIELD_INVALID = "Bcc field contains invalid email address(es)!";
	
    var $STR_NO_ERROR = "No error has occured yet.";

    function checkFields() 
    {
        if(empty($this->to)) {
	    $this->ERROR_MSG = $this->ERR_EMPTY_MAIL_TO;
	    return false;
	}
		
	if(!$this->allow_empty_subject && empty($this->subject)) {
	    $this->ERROR_MSG = $this->ERR_EMPTY_SUBJECT;
	    return false;
	}
		
	if(!$this->allow_empty_body && empty($this->body)) {
	    $this->ERROR_MSG = $this->ERR_EMPTY_BODY;
	    return false;
	}
		
        $this->to = ereg_replace(";", ",", $this->to);
	$this->cc = ereg_replace(";", ",", $this->cc);
	$this->bcc = ereg_replace(";", ",", $this->bcc);
		
	if(!empty($this->from)) $this->headers[] = "From: $this->from";
	if(!empty($this->reply_to)) $this->headers[] = "Reply-To: $this->reply_to";
		
	// Check email addresses if specified so.
	if($this->validate_email) {
	    $to_emails = explode(",", $this->to);
	    if(!empty($this->cc)) $cc_emails = explode(",", $this->cc);
	    if(!empty($this->bcc)) $bcc_emails = explode(",", $this->bcc);
		
	    // Use MX records to furthur check email addresses.
	    if($this->rigorous_email_check) {
	        if(!$this->rigorous_email_check($to_emails)) {
	            $this->ERROR_MSG = $this->ERR_TO_FIELD_INVALID;
	            return false;
	        } 
	        else if(is_array($cc_emails) && !$this->rigorous_email_check($cc_emails)) {
	            $this->ERROR_MSG = $this->ERR_CC_FIELD_INVALID;
	            return false;
	        }
	        else if(is_array($bcc_emails) && !$this->rigorous_email_check($bcc_emails)) {
	            $this->ERROR_MSG = $this->ERR_BCC_FIELD_INVALID;
                    return false;
	        }
	    }else {
		if(!$this->email_check($to_emails)) {
		    $this->ERROR_MSG = $this->ERR_TO_FIELD_INVALID;
		    return false;
		}
		else if(is_array($cc_emails) && !$this->email_check($cc_emails)) {
		    $this->ERROR_MSG = $this->ERR_CC_FIELD_INVALID;
		    return false;
		}
		else if(is_array($bcc_emails) && !$this->email_check($bcc_emails)) {
		    $this->ERROR_MSG = $this->ERR_BCC_FIELD_INVALID;
		    return false;
		}
	    }
	}
	
	    return true;
    }

    function emailCheck($emails) 
    {
        foreach($emails as $email) {
	    if(eregi("<(.+)>", $emails, $match)) $email = $match[1];
	    if(!eregi("^[_\-\.0-9a-z]+@([0-9a-z][_0-9a-z\.]+)\.([a-z]{2,4}$)", $email)) return false;			
	}
	return true;
    }  

    function rigorousEmailCheck($emails) 
    {
        if(!$this->email_check($emails)) return false;
	
        foreach($emails as $email) {
            list ($user, $domain) = split ( "@", $email, 2 );  
	    if(checkdnsrr( $domain, "ANY"))  return true;
	    else {
	        return false;
	    }
	}

    }

    function buildHeaders() 
    {
        if(!empty($this->cc)) $this->headers[] = "Cc: $this->cc";
	if(!empty($this->bcc)) $this->headers[] = "Bcc: $this->bcc";
	
    }

    function viewMsg() 
    {
        if(!$this->check_fields()) return false;
		
	$this->headers = array();
		
	$this->build_headers();
		
	$this->headers[] = "From: $this->from";
	$this->headers[] = "To: $this->to";
	$this->headers[] = "Subject: $this->subject";
	
	$msg = implode("\r\n", $this->headers);
	$msg .= "\r\n\r\n";
	$msg .= $this->body;
		
	return $msg;
    }
   
    function send() 
    {
        if(!$this->check_fields()) return 0;
		
	$this->build_headers();
		
	if(mail($this->to, stripslashes(trim($this->subject)), stripslashes($this->body), implode("\r\n", $this->headers))) return true;
	
        else {
	    $this->ERROR_MSG = $this->ERR_SEND_MAIL_FAILURE;
	    return false;
	}
    }

    function errorMsg() 
    {
        if(empty($this->ERROR_MSG)) 
        {
            return $this->STR_NO_ERROR;
	    return $this->ERROR_MSG;
        }

    }
}
?>
