<?php

// my_smtp_mail_class.php

include("./my_mail_class.php");

class my_smtp_mail extends my_mail 
{
    var $smtp_host = '';
    var $smtp_port = 25;
    var $socket = 0;
	
    var $response_code = 0;
    var $response_msg = '';
	
    var $ERR_SMTP_HOST_NOT_SET = 'SMTP host not set!';
    var $ERR_SMTP_CONNECTION_FAILED = 'Failed to connect to the specified SMTP host!';
    var $ERR_SMTP_NOT_CONNECTED = 'Establish a connection to an SMTP server first!';
	
    var $ERR_COMMAND_UNRECOGNIZED = 'Unrecognizable command!';\
    var $ERR_HELO_WITHOUT_ARG = 'HELO command needs an argument!';
    var $ERR_MAIL_WITHOUT_ARG = 'MAIL FROM command needs an argument!';
    var $ERR_RCPT_WITHOUT_ARG = 'RCPT TO command needs an argument!';
    var $ERR_DATA_WITHOUT_ARG = 'DATA command with empty mail content!';
	
    var $ERR_UNKNOWN_RESPONSE_FROM_SERVER = 'Unknown response from the server!';
    var $ERR_HELO_FAILED = 'HELO command failed!';
    var $ERR_MAIL_FAILED = 'MAIL FROM command failed!';
    var $ERR_RCPT_FAILED = 'RCPT TO command failed!';
    var $ERR_DATA_FAILED = 'DATA command failed!';
    var $ERR_QUIT_FAILED = 'QUIT command failed!';
    var $ERR_INIT_SOCKET_ERROR = "Couldn't initialize the socket!";

    function connect() 
    {
        if(empty($this->smtp_host)) {
	    $this->ERROR_MSG = $this->ERR_SMTP_HOST_NOT_SET;
	    return false;
	}
		
	$this->socket = fsockopen($this->smtp_host, $this->smtp_port, &$err_no, &$err_str);
	
	if(!$this->socket) {
            if(!$err_no) {
	        $err_str = $this->ERR_INIT_SOCKET_ERROR;
	    }	
	    $this->ERROR_MSG = $this->ERR_SMTP_CONNECTION_FAILED . " $err_no: $err_str";
	    return false;
	}

        if(!$this->get_response()) {
	    $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
	    return false;
	}
		
	if($this->response_code != 220) {
			$this->ERROR_MSG = $this->ERR_SMTP_CONNECTION_FAILED . " " . $this->response_code . " " . $this->response_msg;
			return false;
	}
	return true;
    }

    function getResponse() 
    {
        if(!$this->socket) {
	    $this->ERROR_MSG = $this->ERR_SMTP_NOT_CONNECTED;
	    return false;
	}
	
	$server_response = fgets($this->socket, 1024);
	if(ereg("^([0-9]{3}) (.*)$", $server_response, $match)) {
	    $this->response_code = $match[1];
	    $this->response_msg = $match[2];
	    return true;
	}

	$this->response_msg = $server_response;
	return false;
    }

    function talk($cmd, $arg='') 
    {
        if(!$this->socket) {
	    $this->ERROR_MSG = $this->ERR_SMTP_NOT_CONNECTED;
	    return false;
	}
	
	switch ($cmd) {
		
        case "HELO":
	    if(empty($arg)) {
	        $this->ERROR_MSG = $this->ERR_HELO_WITHOUT_ARG;
		return false;
	    }
	    $smtp_cmd = "HELO $arg\r\n";
	    fwrite($this->socket, $smtp_cmd);
	    if(!$this->get_response()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
		return false;
	    }
				
	    if($this->response_code != 250) {
	        $this->ERROR_MSG = $this->ERR_HELO_FAILED . " " . $this->response_code . " " . $this->response_msg;
		return false;
	    }
	    break;
			
        case "MAIL":
            if(empty($arg)) {
	        $this->ERROR_MSG = $this->ERR_MAIL_WITHOUT_ARG;
	        return false;
	    }
	    $smtp_cmd = "MAIL FROM: $arg\r\n";
	    fwrite($this->socket, $smtp_cmd);
				
	    if(!$this->get_response()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
	        return false;
	    }
				
	    if($this->response_code != 250) {
	        $this->ERROR_MSG = $this->ERR_MAIL_FAILED . " " . $this->response_code . " " . $this->response_msg;
		return false;
	    }
			
	    break;
			
			
        case "RCPT":
	    if(empty($arg)) {
	        $this->ERROR_MSG = $this->ERR_RCPT_WITHOUT_ARG;
	        return false;
	    }
		
            $to_emails = explode(",", $arg);
				
	    foreach($to_emails as $email) {
	        $smtp_cmd = "RCPT TO: $email\r\n";
	        fwrite($this->socket, $smtp_cmd);
				
		if(!$this->get_response()) {
		    $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
		    return false;
		}
					
		if($this->response_code != 250) {
		    $this->ERROR_MSG = $this->ERR_RCPT_FAILED . " " . $this->response_code . " " . $this->response_msg;
		    return false;
		}
            }
				
	    break;
			
        case "DATA":
	    if(empty($arg)) {
	        $this->ERROR_MSG = $this->ERR_DATA_WITHOUT_ARG;
		return false;
	    }
		
            smtp_cmd = "DATA\r\n";
	    fwrite($this->socket, $smtp_cmd);;
		
	    if(!$this->get_response()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
		return false;
	    }
				
	    if($this->response_code != 354) {
	        $this->ERROR_MSG = $this->ERR_DATA_FAILED . " " . $this->response_code . " " . $this->response_msg;
		return false;
	    }
				
	    $smtp_cmd = "$arg\r\n" . "." . "\r\n";
	    fwrite($this->socket, $smtp_cmd);
		
	    if(!$this->get_response()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
		return false;
	    }
	    
            if($this->response_code != 250) {
	        $this->ERROR_MSG = $this->ERR_DATA_FAILED . " " . $this->response_code . " " . $this->response_msg;
		return false;
	    }
				
	    break;
			
	case "QUIT":
	    $smtp_cmd = "QUIT\r\n";
	    fwrite($this->socket, $smtp_cmd);
		
	    if(!$this->get_response()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
		return false;
	    }
				
	    if($this->response_code != 221) {
	        $this->ERROR_MSG = $this->ERR_QUIT_FAILED . " " . $this->response_code . " " . $this->response_msg;
		return false;
	    }
				
	    break;
			
        default:
	    $this->ERROR_MSG = $this->ERR_COMMAND_UNRECOGNIZED;
	    return false;
		
	    break;
	    }

            return true;

    }

    function send() 
    {

        if(!$this->check_fields()) return false;
		
	$this->build_headers();
		
	if(!$this->connect()) return false;
		
	if(!$this->talk("HELO", $GLOBALS["SERVER_NAME"])) return false;
	if(!$this->talk("MAIL", $this->from)) return false;
	if(!$this->talk("RCPT", $this->to)) return false;
		
	if(!empty($this->to)) $this->headers[] = "To: $this->to";
	if(!empty($this->subject)) $this->headers[] = "Subject: $this->subject";
		
	if(!$this->talk("DATA", implode("\r\n", $this->headers) . "\r\n\r\n" . $this->body)) return false;
	if(!$this->talk("QUIT")) return false;
		
	fclose($this->socket);
		
	return true;
   }
}
?>
