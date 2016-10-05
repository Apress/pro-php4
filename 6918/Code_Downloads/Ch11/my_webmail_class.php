<?php

// my_webmail_class.php

class My_Webmail 
{
    var $sendmail_class = 'my_mime_mail';
    var $smtp_class = 'my_smtp_mime_mail';
    var $nntp_class = 'my_nntp';
    var $smtp_host = '';
    var $smtp_port = 24;
	
    var $nntp_host = '';
    var $nntp_port = 119;
    var $HTML_TITLE = 'Welcome to My Webmail!';
    var $CHARSET = '';
    var $ERROR_MSG = '';

    function start($action) 
    { 	
        switch($action) {
	case 'mail':
	    if(!$this->send_webmail()) return false;
	    echo $this->mail_form();
	    break;
	default:
	    echo $this->mail_form();
	    break;
	}
		
	return true;
    }

    function sendWebmail() 
    {
	global $is_news, $nntp_host, $nntp_port, $use_smtp, $smtp_host, $smtp_port;
	global $mail_to, $mail_references, $mail_from, $mail_reply_to, $mail_cc, $mail_bcc;
	global $mail_type, $mail_charset, $mail_subject, $mail_body;
	global $userfile, $userfile_type, $userfile_name, $userfile_size;
	
	if($is_news) {
	    include("./my_nntp_class.php");
	        $my_mail = new $this->nntp_class();
		$my_mail->nntp_host = $nntp_host;
		$my_mail->nntp_port = $nntp_port;
		$my_mail->newsgroups = $mail_to;
	    }
            else { if($use_smtp) {
	        include("./my_smtp_mime_mail_class.php");
		
		$my_mail = new $this->smtp_class();
		$my_mail->smtp_host = $smtp_host;
		$my_mail->smtp_port = $smtp_port;
	    }
	    else {
	        include("./my_mime_mail_class.php");         
		
                $my_mail = new $this->sendmail_class();
	    }
			
	    $my_mail->to = $mail_to;
	    $my_mail->cc = $mail_cc;
	    $my_mail->bcc = $mail_bcc;
	}
		
	$my_mail->from = $mail_from;
	$my_mail->type = $mail_type;
	$my_mail->charset = $mail_charset;
	$my_mail->subject = $mail_subject;
	$my_mail->body = $mail_body;
	
        if($userfile_size > 0) {
	    $my_mail->files[0]["file"] = $userfile;
	    $my_mail->files[0]["filename"] = $userfile_name;
	    $my_mail->files[0]["filesize"] = $userfile_size;
	    $my_mail->files[0]["filetype"] = $userfile_type;
	}
		
	if(!$my_mail->send()) {
	    $this->build_error_msg($my_mail->error_msg());
	    return false;
	}
		
	if($is_news) $phrase = 'posted';
	else ($phrase = 'sent');
	
        echo ("<script language=\"JavaScript\">alert(\"Successfully $phrase '$mail_subject'!\"); history.go(-1);</script>");
		
	return true;
	
    }

    function mailForm() 
    {
        global $PHP_SELF;

	$ret_str = $this->html_header($this->HTML_TITLE);
	$ret_str .="<form name=\"MAIL_FORM\" action=\"$PHP_SELF\" METHOD=\"POST\" enctype=\"MULTIPART/FORM-DATA\">\n";
	$ret_str .= "<input type=\"HIDDEN\" value=\"mail\" name=\"action\">\n";
	$ret_str .= "<div align=\"CENTER\"><table cellspacing=\"2\" cellpadding=\"5\" width=\"90%\" border=\"1\">\n";
		
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"100%\" colspan=\"2\"><input type=\"CHECKBOX\" name=\"is_news\" value=\"ON\">POST NEWS ARTICLE</th>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
		
	$ret_str .= "<th width=\"30%\">NNTP HOST</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"nntp_host\" size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">NNTP PORT</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"nntp_port\" size=\"4\" value=\"119\"></td>\n";
	$ret_str .= "</tr>\n";
	
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"100%\" colspan=\"2\"><input type=\"CHECKBOX\" name=\"use_smtp\" value=\"ON\">USE SMTP</th>\n";
	$ret_str .= "</tr>\n";
		
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">SMTP HOST</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"smtp_host\" size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">SMTP PORT</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"smtp_port\" size=\"5\" value=\"25\"></td>\n";
	$ret_str .= "</tr>\n";
	
	$ret_str .= "<tr>\n";	
	$ret_str .= "<th width=\"30%\">Newsgroups/To</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"mail_to\" value=\"$mail_to\" size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">CC</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"mail_cc\" value=\"$mail_cc\" size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">BCC</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"TEXT\" name=\"mail_bcc\" size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">FROM</th>\n";
	$ret_str .= "<td width=\"70%\"><input name=\"mail_from\" size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">REPLY-TO</th>\n";
	$ret_str .= "<td width=\"70%\"><input name=\"mail_reply_to\" value=\"$mail_reply_to\"size=\"20\"></td>\n";
	$ret_str .= "</tr>\n";
		
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">ATTACHMENT</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"FILE\" name=\"userfile\"></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	
	$ret_str .= "<th width=\"30%\">type</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"RADIO\" checked value=\"text\" name=\"mail_type\">TEXT\n";
	$ret_str .= "<input type=\"RADIO\" value=\"html\" name=\"mail_type\">HTML\n";
	$ret_str .= "</td>\n";
	$ret_str .= "</tr>\n";
		
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">ENCODING</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"RADIO\" value=\"7bit\" name=\"mail_encoding\" checked>7BIT\n";
	$ret_str .= "<input type=\"RADIO\" value=\"8bit\" name=\"mail_encoding\">8BIT\n";
	$ret_str .= "</td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	
	$ret_str .= "<th width=\"30%\">CHARACTER SET</th>\n";
	$ret_str .= "<td width=\"70%\"><input type=\"RADIO\" value=\"us-ascii\" name=\"mail_charset\" checked>US-ASCII\n";
	$ret_str .= "<input type=\"RADIO\" value=\"euc-kr\" name=\"mail_charset\">EUC-KR\n";
	$ret_str .= "</td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">SUBJECT</th>\n";
	$ret_str .= "<td width=\"70%\"><input size=\"40\" name=\"mail_subject\" value=\"$mail_subject\"></td>\n";
	$ret_str .= "</tr>\n";
		
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\">BODY</th>\n";
	$ret_str .= "<td width=\"70%\"><TEXTAREA name=\"mail_body\" ROWS=\"10\" COLS=\"60\">$mail_body</TEXTAREA></td>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "<tr>\n";
	$ret_str .= "<th width=\"30%\" COLSPAN=\"2\"><input type=\"SUBMIT\" value=\"Send\" name=\"SUBMIT\">\n";
	$ret_str .= "<input type=\"RESET\" value=\"Reset\" name=\"RESET\"></th>\n";
	$ret_str .= "</tr>\n";
	$ret_str .= "</table>\n";
	$ret_str .= "</DIV>\n";
	$ret_str .= "</form>\n";
	
	$ret_str .= $this->htmlFooter();
		
	return $ret_str;
    }

    function htmlHeader($title='', $charset='') 
    {
        $ret_str = "<html>\n";
	$ret_str .= "<head>\n";
	if(!empty($charset)) $ret_str .= "<meta http-equiv=\"CONTENT-TYPE\" content=\"TEXT/HTML; CHARSET=$charset\">\n";
	$ret_str .= "<title>$title</title>\n";
	$ret_str .= "</head>\n";
	$ret_str .= "<body>\n";
		
	return $ret_str;
    }

    function htmlFooter() 
    {
        $ret_str = "</body>\n";
	$ret_str .= "</html>\n";
		
	return $ret_str;
    }

    function buildErrorMsg($err_msg, $err_arg='') 
    {
        $this->ERROR_MSG = $err_msg . $this->ERR_ARGS_DELIMITER . $err_arg;
    }

    function errorMsg() {
        return $this->ERROR_MSG;
    }
}
?>
