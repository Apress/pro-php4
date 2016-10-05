<?php

// my_nntp_class.php

include("./my_smtp_mime_mail_class.php");

class My_Nntp extends My_Smtp_Mime_Mail
{
    var $nntp_host = '';
    var $nntp_port = 119;
    var $newsgroups = ''; 
	
    var $references = '';
	
    var $ERR_NNTP_HOST_NOT_SET = 'NNTP host not set!';
    var $ERR_NNTP_CONNECTION_FAILED = 'Failed to connect to the specified NNTP host!';
    var $ERR_NNTP_NOT_CONNECTED = 'Establish a connection to an NNTP server first!';
	
    var $ERR_EMPTY_FROM = "Empty From header!";
    var $ERR_EMPTY_NEWSGROUPS = "No newsgroup(s) specified!";
	
    var $ERR_GROUP_WITHOUT_ARG = 'GROUP command needs an argument!';
    var $ERR_POST_WITHOUT_ARG = 'POST command with empty article content!';
	
    var $ERR_UNKNOWN_RESPONSE_FROM_SERVER = 'Unknown response from the server!';
    var $ERR_POSTING_NOT_ALLOWED  = "Posting not allowed on this server!";
    var $ERR_GROUP_POSTING_NOT_ALLOWED = "Posting not allowed on this newsgroup!";
    var $ERR_GROUP_FAILED = 'GROUP command failed!';
    var $ERR_NO_SUCH_GROUP = 'No such group!';
    var $ERR_POST_FAILED = 'POST command failed!';
    var $ERR_QUIT_FAILED = 'QUIT command failed!';

    function connect() 
    {
        if(empty($this->nntp_host)) {
            $this->ERROR_MSG = $this->ERR_NNTP_HOST_NOT_SET;
	    return false;
        }
		
        $this->socket = fsockopen($this->nntp_host, $this->nntp_port, &$err_no, &$err_str);
		
        if(!$this->socket) {
            if(!$err_no) $err_str = $this->ERR_INIT_SOCKET_ERROR;
	    $this->ERROR_MSG = $this->ERR_NNTP_CONNECTION_FAILED . " $err_no: $err_str";
	    return false;
        }
        if(!$this->getResponse()) {
            $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
	    return false;
        }
        if($this->response_code == 200) return true;
        else if($this->response_code == 201) {
            $this->ERROR_MSG = $this->ERR_POSTING_NOT_ALLOWED; 
            return false;
        } else {
	    $this->ERROR_MSG = $this->ERR_NNTP_CONNECTION_FAILED;
	    return false;
        }
		
        return true;
    }

    function getResponse() 
    {
        if(!$this->socket) {
	    $this->ERROR_MSG = $this->ERR_NNTP_NOT_CONNECTED;
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
	    $this->ERROR_MSG = $this->ERR_NNTP_NOT_CONNECTED;
	    return false;
	}

	switch ($cmd) {
		
	case "GROUP":
            if(empty($arg)) {
	        $this->ERROR_MSG = $this->ERR_GROUP_WITHOUT_ARG;
		return false;
	    }
            $nntp_cmd = "GROUP $arg\r\n";
	    fwrite($this->socket, $nntp_cmd);
	    if(!$this->getResponse()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
		return false;
	    }
	    if($this->response_code != 211 && $this->response_code != 411) {
	        $this->ERROR_MSG = $this->ERR_GROUP_FAILED . " " . $this->response_code . " " . $this->response_msg;
		return false;
            }
	    if($this->response_code == 411) {
	        $this->ERROR_MSG = $this->ERR_NO_SUCH_GROUP . " " . $this->response_code . " " . $this->response_msg . " " . $arg;
		return false;
	    }
			
	    break;
		
        case "POST":
	    if(empty($arg)) {
	        $this->ERROR_MSG = $this->ERR_POST_WITHOUT_ARG;
		return false;
	    }
	    $nntp_cmd = "POST\r\n";
	    fwrite($this->socket, $nntp_cmd);;
	    if(!$this->getResponse()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
                return false;
	    }
	    if($this->response_code != 340 && $this->response_code != 440) {
	        $this->ERROR_MSG = $this->ERR_POST_FAILED . " " . $this->response_code . " " . $this->response_msg;
	        return false;
	    }
		
	    if($this->response_code == 440) {
	        $this->ERROR_MSG = $this->ERR_GROUP_POSTING_NOT_ALLOWED . " " . $this->response_code . " " . $this->response_msg;
	        return false;
	    }
		
	    $nntp_cmd = "$arg\r\n" . "." . "\r\n";
	    fwrite($this->socket, $nntp_cmd);
	    if(!$this->getResponse()) {
	        $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
	        return false;
	    }
		
	    if($this->response_code != 240) {
	        $this->ERROR_MSG = $this->ERR_POST_FAILED . " " . $this->response_code . " " . $this->response_msg;
	        return false;
            }
		
	     break;

        case "QUIT":
	    $nntp_cmd = "QUIT\r\n";
	    fwrite($this->socket, $nntp_cmd);
	    if(!$this->getResponse()) {
                $this->ERROR_MSG = $this->ERR_UNKNOWN_RESPONSE_FROM_SERVER . ":" . $this->response_msg;
                return false;
	    }
	    if($this->response_code != 205) {
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

    function buildHeaders() 
    {
        if(empty($this->from)) {
            $this->ERROR_MSG = $this->ERR_EMPTY_FROM;
	    return false;
	}
	else if(empty($this->subject)) {
	    $this->ERROR_MSG = $this->ERR_EMPTY_SUBJECT;
	    return false;      
	}
	else if(empty($this->body)) {
	    $this->ERROR_MSG = $this->ERR_EMPTY_BODY;
	    return false;
	}
	else if(empty($this->newsgroups)) {
	    $this->ERROR_MSG = $this->ERR_EMPTY_NEWSGROUPS;
	    return false;
	}
		
	$this->headers[]  = "From: $this->from";
	if(!empty($this->reply_to)) $this->headers[]  = "Reply-To: $this->reply_to";
		
	if(!empty($this->references)) $this->headers[]  = "References: $this->references";
		
	$this->headers[]  = "Newsgroups: " . ereg_replace("[ ;]", ",", $this->newsgroups);
	$this->headers[] = "Subject: $this->subject";
	return true;
    }

    function viewMsg() 
    {
        if(count($this->files) > 0) $this->has_attach = true;
		
	$this->headers = array();
	$this->buildHeaders();
	
	$this->buildMimeHeaders();
		
	if(!$this->build_body_parts()) return false;
		
	$msg = implode("\r\n", $this->headers);
		
	$msg .= "\r\n\r\n";
	$msg .= $this->body;
		
	return $msg;
    }

    function send() 
    {
        if(count($this->files) > 0) $this->has_attach = true;
		
	if(!$this->buildHeaders()) return false;
		
	$this->buildMimeHeaders();
	if(!$this->build_body_parts()) return false;
		
	if(!$this->connect()) return false;
		
	$this->newsgroups = ereg_replace("[ \t]", "", $this->newsgroups);
	
	$newsgroups = explode(",", $this->newsgroups);
		
	foreach ($newsgroups as $group) if(!$this->talk("GROUP", $group)) return false;
		
	if(!$this->talk("POST", implode("\r\n", $this->headers) . "\r\n\r\n" . $this->body)) return false;
		if(!$this->talk("QUIT")) return false;
		
	fclose($this->socket);
		
	return true;
    }
}
?>
