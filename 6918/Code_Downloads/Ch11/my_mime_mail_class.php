<?php

// my_mime_mail_class.php

include("./my_mail_class.php");

class My_Mime_Mail extends My_Mail 
{
    var $type = 'text/plain';
    var $charset = 'us-ascii';
	
    var $encoding = '7bit';
	
    var $has_attach = 0;
    var $files = array();
	
    var $mime_type = 'application/octet-stream';
    var $mime_version = "MIME-Version: 1.0";
    var $mime_msg = "This is a multi-part message in MIME format.";
	
    var $mailer = 'My Mime Mailer 1.0';
	
    var $boundary = '';
	
    var $ERR_CANNOT_OPEN_FILE = 'Cannot open the specified file!';

    function buildMimeHeaders() 
    {
        $this->headers[] = "X-Mailer: " . $this->mailer;
        $this->headers[] = $this->mime_version;
		
        if($this->has_attach) {
            $this->boundary = md5(uniqid(time()));
  	    $this->headers[] = "Content-Type: multipart/mixed; boundary=\"$this->boundary\"\r\n";
	    $this->headers[] = $this->mime_msg . "\r\n";
	    $this->headers[] = "--" . $this->boundary;
        }
	
        $this->headers[] = "Content-Type: $this->type; charset=$this->charset";
        $this->headers[] = "Content-Transfer-Encoding: $this->encoding";
	
    }


    function buildBodyParts() 
    {
        if(!$this->has_attach) return true;
        $body_parts[0] .= $this->body . "\r\n\r\n"; 
	
        for($i=0; $i < count($this->files); $i++) {
            if(!($fp = @fopen($this->files[$i]["file"], "r"))) {
	        $this->ERROR_MSG = $this->ERR_CANNOT_OPEN_FILE . " " . $this->files[$i]["file"];
	        return false;
	    }
			
	$file_body = fread($fp, filesize($this->files[$i]["file"]));
	$file_body = chunk_split(base64_encode($file_body));
			
	$body_parts[$i+1] = "--" . $this->boundary . "\r\n";
			
	if(!empty($this->files[$i]["filetype"])) $this->mime_type = $this->files[$i]["filetype"];
			
	    $body_parts[$i+1] .= "Content-Type: " . $this->mime_type . ";name=" . basename($this->files[$i]["filename"]) .  "\r\n";
	    $body_parts[$i+1] .= "Content-Transfer-Encoding: base64\r\n\r\n";
	    $body_parts[$i+1] .= $file_body . "\r\n\r\n";
	}
	
	$body_parts[$i+1] .= "--" . $this->boundary . "--";
	$this->body = implode("", $body_parts);
		
	return true;
	
    }

    function viewMsg() 
    {
        if(count($this->files) > 0) $this->has_attach = true;
        if(!$this->check_fields()) return false;
		
        $this->headers = array();
        $this->build_headers();
		
        $this->headers[] = "From: $this->from";
        $this->headers[] = "To: $this->to";
        $this->headers[] = "Subject: $this->subject";
		
        $this->build_mime_headers();
        if(!$this->build_body_parts()) return false;
		
        $msg = implode("\r\n", $this->headers);
		
        $msg .= "\r\n\r\n";
        $msg .= $this->body;
		
        return $msg;
    }

    function send() 
    {
        if(count($this->files) > 0) $this->has_attach = true;
		
        if(!$this->check_fields()) return false;
		
        $this->subject = stripslashes(trim($this->subject));
        $this->body = stripslashes($this->body);
		
        $this->build_headers();
        $this->build_mime_headers();
        if(!$this->build_body_parts()) return false;
		
        if(mail($this->to, $this->subject, $this->body, implode("\r\n", $this->headers))) return true;
        else {
            $this->ERROR_MSG = $this->ERR_SEND_MAIL_FAILURE;
            return false;
        }
    }
}
?>
