<?php

// webmail.php

include("./webmail_class_final.php");

class My_Webmail extends Webmail 
{
    var $sendmail_class = 'my_mime_mail';
    var $smtp_class = 'my_smtp_mime_mail';
    var $nntp_class = 'my_nntp';
	
    var $smtp_host = '';
    var $smtp_port = 24;
    var $sent_mailbox = '';
    var $msg_per_page = 10;
	
    var $HTML_TITLE = 'Welcome to My Webmail!';
    var $CHARSET = 'EUC-KR';
	
    var $STR_NO_MESSAGE = 'No message.';
    var $ERR_STR_NO_UIDS  = 'No message selected!';

    function start($action) 
    {
        switch($action) {
        case 'read_msg':
            $msg = $this->read_msg();
	    if(!$msg) return 0;
	    return $this->interface('', $msg);
            break;
        case 'download_attachment':
            if(!$this->download_attachment()) return false;
            break;
 	case 'create_mailbox': 
            if(!$this->create_mailbox()) return false;
            return $this->interface('', '');
            break;        
        case 'rename_mailbox': 
            if(!$this->rename_mailbox()) return false;
            return $this->interface('', '');
            break;
        case 'delete_mailbox':
            if(!$this->delete_mailbox()) return false;
            return $this->interface('', '');
            break;
        case 'copy_msg':
	    if(!$this->copy_msg()) return false;
            return $this->interface('', '');
             break;
        case 'move_msg':
	    if(!$this->move_msg()) return false;
	    return $this->interface('', '');
            break;
        case 'delete_msg':
            if(!$this->delete_msg()) return false;
            return $this->interface('', '');
            break;
        case 'mail_form':
            return $this->interface('', $this->mail_form());
            break;
        case 'mail':
            return $this->send_webmail();
            break;
        default:
            return $this->interface('');
            break;
        }
		
        return true;
        }

    function interface($first_col, $second_col='') 
    {
	
        $mailboxes = $this->list_mailbox();
	if(!$mailboxes) return false;
		
	$first_col = $mailboxes . $this->create_mailbox_form() . $this->menu() . $first_col;
		
	if(empty($second_col)) {
	    $msgs = $this->list_msg();
	    if(!$msgs) $msgs = $this->STR_NO_MESSAGE;
	    $second_col = $msgs;      
 	}
		
	echo $this->htmlHeader($this->HTML_TITLE,$this->CHARSET);
		
	echo("<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLSPACING=\"0\" CELLPADDING=\"0\">\n");
	echo("<TR>\n");
	echo("<TD WIDTH=\"30%\" VALIGN=\"TOP\">$first_col</TD>\n");
	echo("<TD WIDTH=\"70%\" VALIGN=\"TOP\">$second_col</TD>\n");
	echo("</TR>\n");
	echo("</TABLE>\n");
		
	echo $this->htmlFooter();
		
	return true;
    }

    function menu() 
    {
        if($this->protocol == 'nntp') $menu_str = $this->build_url("action=mail_form&mode=article&mailbox=$this->mailbox", '[Post]');
	else $menu_str = $this->build_url('action=mail_form&mode=new', '[Send]');
		
	return $menu_str;
    }

    function mailForm() 
    {
        global $PHP_SELF, $mode, $email;
		
	$is_news = false;
		
	if($mode == 'reply') {
	    $msg = $this->getMsg('', '');
	    if(!$msg) $mail_to = $email;
	    else {
	        $mail_date = $msg["date"];
                $mail_to = $msg["raw_from"];
	        $mail_cc = $msg["raw_cc"];
                $mail_subject = "Re: " . $msg["subject"];
	        $mail_body = "--- Original Message($mail_date) ---\r\n" . eregi_replace("<BR>", "\r\n", $msg["body"]);
	    }
	}
	else if($mode == 'forward') {
	    $msg = $this->getMsg('', '');
	    if(!$msg) return false;
	    $mail_from = $msg["raw_from"];
	    $mail_date = $msg["date"];
	    $mail_subject = "Fwd: " . $msg["subject"];
	    $mail_reply_to = $mail_from;
	    $mail_body = "--- Original Message($mail_date) ---\r\n" . eregi_replace("<BR>", "\r\n", $msg["body"]);
        }
	else if($mode == 'article') {
	    $mail_to = $this->mailbox;
	    $is_news = true;
	}
	else if($mode == 'followup') {
	    $mail_to = $this->mailbox;
	    $msg = $this->getMsg('', '');
	    $mail_references = $msg["references"] . " " . $msg["message_id"];
	    $mail_subject = "Re: " . $msg["subject"];
	    $is_news = true;
	}
		
	$ret_str ="<FORM NAME=\"MAIL_FORM\" ACTION=\"$PHP_SELF\" METHOD=\"POST\" ENCTYPE=\"MULTIPART/FORM-DATA\">\n";
	$ret_str .= "<INPUT TYPE=\"HIDDEN\" VALUE=\"mail\" NAME=\"action\">\n";
	$ret_str .= "<INPUT TYPE=\"HIDDEN\" VALUE=\"$mode\" NAME=\"mode\">\n";
	$ret_str .= "<INPUT TYPE=\"HIDDEN\" VALUE=\"$this->msg_uid\" NAME=\"msg_uid\">\n";
	$ret_str .= "<DIV ALIGN=\"CENTER\"><TABLE CELLSPACING=\"2\" CELLPADDING=\"5\" WIDTH=\"90%\" BORDER=\"1\">\n";
	
	if(!$is_news) {
	    $ret_str .= "<TR>\n";  
	    $ret_str .= "<TH WIDTH=\"100%\" COLSPAN=\"2\"><INPUT TYPE=\"CHECKBOX\" NAME=\"use_smtp\" VALUE=\"ON\">USE SMTP</TH>\n";   
	    $ret_str .= "</TR>\n";
	    $ret_str .= "<TR>\n";	
	    $ret_str .= "<TH WIDTH=\"30%\">SMTP HOST</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"TEXT\" NAME=\"smtp_host\" SIZE=\"20\"></TD>\n";	         
	    $ret_str .= "</TR>\n";
	    $ret_str .= "<TR>\n";   
	    $ret_str .= "<TH WIDTH=\"30%\">SMTP PORT</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"TEXT\" NAME=\"smtp_port\" SIZE=\"5\" VALUE=\"25\"></TD>\n";	
	    $ret_str .= "</TR>\n";   
	    $ret_str .= "<TR>\n";
	    $ret_str .= "<TH WIDTH=\"30%\">TO</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"TEXT\" NAME=\"mail_to\" VALUE=\"$mail_to\" SIZE=\"20\"></TD>\n";
	    $ret_str .= "</TR>\n";   	
	    $ret_str .= "<TR>\n";		
	    $ret_str .= "<TH WIDTH=\"30%\">CC</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"TEXT\" NAME=\"mail_cc\" VALUE=\"$mail_cc\" SIZE=\"20\"></TD>\n";
	    $ret_str .= "</TR>\n";
	    $ret_str .= "<TR>\n";
	    $ret_str .= "<TH WIDTH=\"30%\">BCC</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"TEXT\" NAME=\"mail_bcc\" SIZE=\"20\"></TD>\n";
	    $ret_str .= "</TR>\n";
	}
        else {
	    $ret_str .= "<TR>\n";
	    $ret_str .= "<TH WIDTH=\"30%\">Newsgroups</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"TEXT\" NAME=\"mail_to\" VALUE=\"$mail_to\" SIZE=\"20\"></TD>\n";
	    $ret_str .= "</TR>\n";
        }
		
	if(!empty($mail_references)) {
	    $ret_str .= "<TR>\n";
	    $ret_str .= "<TH WIDTH=\"30%\">References</TH>\n";
	    $ret_str .= "<TD WIDTH=\"70%\">$mail_references<INPUT TYPE=\"HIDDEN\" NAME=\"mail_references\" VALUE=\"$mail_references\" SIZE=\"20\"></TD>\n";
	    $ret_str .= "</TR>\n";	
	}
		
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">FROM</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT NAME=\"mail_from\" SIZE=\"20\"></TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">REPLY-TO</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT NAME=\"mail_reply_to\" VALUE=\"$mail_reply_to\"SIZE=\"20\"></TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">ATTACHMENT</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"FILE\" NAME=\"userfile\"></TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">TYPE</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"RADIO\" CHECKED VALUE=\"text\" NAME=\"mail_type\">TEXT\n";
	$ret_str .= "<INPUT TYPE=\"RADIO\" VALUE=\"html\" NAME=\"mail_type\">HTML\n";
	$ret_str .= "</TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">ENCODING</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"RADIO\" VALUE=\"7bit\" NAME=\"mail_encoding\" CHECKED>7BIT\n";
	$ret_str .= "<INPUT TYPE=\"RADIO\" VALUE=\"8bit\" NAME=\"mail_encoding\">8BIT\n";
	$ret_str .= "</TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">CHARACTER SET</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT TYPE=\"RADIO\" VALUE=\"us-ascii\" NAME=\"mail_charset\" CHECKED>US-ASCII\n";
	$ret_str .= "<INPUT TYPE=\"RADIO\" VALUE=\"euc-kr\" NAME=\"mail_charset\">EUC-KR\n";
	$ret_str .= "</TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">SUBJECT</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><INPUT SIZE=\"40\" NAME=\"mail_subject\" VALUE=\"$mail_subject\"></TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\">BODY</TH>\n";
	$ret_str .= "<TD WIDTH=\"70%\"><TEXTAREA NAME=\"mail_body\" ROWS=\"10\" COLS=\"60\">$mail_body</TEXTAREA></TD>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "<TR>\n";
	$ret_str .= "<TH WIDTH=\"30%\" COLSPAN=\"2\"><INPUT TYPE=\"SUBMIT\" VALUE=\"Send\" NAME=\"SUBMIT\">\n";
	$ret_str .= "<INPUT TYPE=\"RESET\" VALUE=\"Reset\" NAME=\"RESET\"></TH>\n";
	$ret_str .= "</TR>\n";
	$ret_str .= "</TABLE>\n";
	$ret_str .= "</DIV>\n";
	$ret_str .= "</FORM>\n";
	
	return $ret_str;
    }

    function sendWebmail() 
    {
        global $is_news, $use_smtp, $smtp_host, $smtp_port;
        global $mail_to, $mail_references, $mail_from, $mail_reply_to, $mail_cc, $mail_bcc;
        global $mail_type, $mail_charset, $mail_subject, $mail_body;
        global $userfile, $userfile_type, $userfile_name, $userfile_size;
            
        if($is_news) {
            include ("./my_nntp_class.php");
      
            $my_mail = new $this->nntp_class();
            $my_mail->nntp_host = $nntp_host;
            $my_mail->nntp_port = $nntp_port;
            $my_mail->newsgroups = $mail_to;
        }
        else {
            if($use_smtp) {
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
            $this->buildErrorMsg($my_mail->error_msg());
            return 0;
        }

        $mail_str = $my_mail->view_msg();
        if(!$mail_str) return false;

	if(!empty($this->sent_mailbox)) {
            if(!$this->append_mail($mail_str, $this->sent_mailbox)) return false;         
        }
      
        if($is_news) $phrase = 'posted';
        else $phrase = 'sent';
        echo "<SCRIPT LANGUAGE=\"JavaScript\">alert(\"Successfully $phrase '$mail_subject'!\");history.go(-1);</SCRIPT>";
      
        return true;
      
    }

    function copy_msg() 
    {
        global $MSG_UIDS;
		
	if(!is_array($MSG_UIDS)) {
	    $this->buildErrorMsg($this->ERR_STR_NO_UIDS);
	    return false;
	}
		
	if(!$this->copyMailMsg(implode(",", $MSG_UIDS))) return false; 
	    return true;
	}

        function moveMsg() 
        {
	    global $MSG_UIDS;
		
	    if(!is_array($MSG_UIDS)) {
	        $this->buildErrorMsg($this->ERR_STR_NO_UIDS);
	        return false;
	    }
		
	    if(!$this->moveMailMsg(implode(",", $MSG_UIDS))) return 0; 
	    return true;
	}

	function deleteMsg() 
        {
	    global $MSG_UIDS;
		
	    if(!is_array($MSG_UIDS)) {
	        $this->buildErrorMsg($this->ERR_STR_NO_UIDS);
	        return true;
	    }
		
	    if(!$this->deleteMailMsg(implode(",", $MSG_UIDS))) return 0; 
   	    return true;
	}

	function createMailboxForm() 
        {
	    global $PHP_SELF;
		
	    if($this->protocol != 'imap') return;
	    $ret_str = "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
	    $ret_str .= "<INPUT TYPE=\"HIDDEN\" NAME=\"action\" VALUE=\"create_mailbox\">\n";
	    $ret_str .= "<INPUT TYPE=\"TEXT\"NAME=\"new_mailbox\" SIZE=\"10\"><BR>\n";
	    $ret_str .= "<INPUT TYPE=\"SUBMIT\" VALUE=\"Create\" NAME=\"SUBMIT\">\n";
	    $ret_str .= "</FORM>\n";
		
	    return $ret_str;
	}

	function msgTableHeader()
        {
	    return "<TABLE BORDER=\"1\" WIDTH=\"90%\" CELLPADDING=\"2\" CELLSPACING=\"1\">\n";
	}

	function msgTableRow($width, $cell_data, $is_th=0, $bg_color='#FFFFFF', $align='CENTER', $valign='TOP') 
        {
	    if(!$is_th) $row_tag = 'TD';
	    else $row_tag = 'TH';
	 
	    return "<$row_tag WIDTH=\"$width%\" ALIGN=\"$align\" VALIGN=\"$valign\" BGCOLOR=\"$bg_color\" NOWRAP>$cell_data</$row_tag>\n";
	}
	
        function msgTableFooter() 
        {
            return "</TABLE>\n";
        }

	function listMsg() 
        {
	    global $PHP_SELF, $cur_page;
	    $order = $this->reverse;
		
	    if($this->sort == 'SORTDATE') $this->reverse = (integer)!$this->reverse;
		
	    if($this->protocol =='imap') {
	        $ret_str = "<FORM METHOD=\"POST\" ACTION=\"$PHP_SELF\">\n";
		$ret_str .= "<INPUT TYPE=\"HIDDEN\" NAME=\"mailbox\" VALUE=\"$this->mailbox\">\n";
	    }
	    else $ret_str = '';
	
	    $ret_str .= $this->msgTableHeader();
		
	    $t_str = $this->msgTableRow(10,'NO',1,"#CECECE");
		
	    $t_str .= $this->msgTableRow(50,$this->build_url("action=list_msg&mailbox=$this->mailbox&sort=SORTSUBJECT&reverse=$order", "SUBJECT"),1,"#CECECE");
		
	    $t_str .= $this->msgTableRow(20,$this->build_url("action=list_msg&mailbox=$this->mailbox&sort=SORTFROM&reverse=$order", "FROM"),1,"#CECECE");
		
	    $t_str .= $this->msgTableRow(20,$this->build_url("action=list_msg&mailbox=$this->mailbox&sort=SORTDATE&reverse=$this->reverse", "DATE"),1,"#CECECE");
		
	    $ret_str .= "<TR>\n$t_str</TR>\n";
		
	    $msgs = $this->getMsgList('action=read_msg', 'action=mail_form&mode=reply');
	    
            if(!$msgs) return 0;
		
	    $num_msg = count($msgs);
		
	    if(!$cur_page) $cur_page = 1;
	    
            if(!$num_msg) $num_page =1;
	    else $num_page = ceil($num_msg/$this->msg_per_page);
		
	    if($cur_page >= $num_page) $cur_page = $num_page;
		
	    $mailboxes = $this->getMailboxList('', 1);
	    if(!$mailboxes) return false;
		
	    $start_num = ($cur_page - 1) * $this->msg_per_page;
	    $end_num = $cur_page * $this->msgPerPage;
		
	    if($end_num > $num_msg) $end_num = $num_msg;
		
	    for($i= $start_num; $i < $end_num; $i++) {
		
	        $msg_no = $msgs[$i]["no"];
		$msg_uid = $msgs[$i]["uid"];
		
		if(count($mailboxes) > 0 && $this->protocol =='imap') $checkbox = "<INPUT TYPE=\"CHECKBOX\" NAME=\"MSG_UIDS[]\" VALUE=\"$msg_uid\">";
		
		$msg_subject = $msgs[$i]["subject"];
		$msg_from = $msgs[$i]["from"];
		$msg_date = $msgs[$i]["date"];
		if($msgs[$i]["unseen"]) $is_th = 1;
		else $is_th = false;
		
                $t_str = $this->msg_table_row(10,$checkbox . $msg_no, $is_th, '#FFFFFF', 'LEFT');
		$t_str .= $this->msg_table_row(50,$msg_subject, $is_th, '#FFFFFF', 'LEFT');
		$t_str .= $this->msg_table_row(20,$msg_from, $is_th, '#FFFFFF', 'LEFT');
		$t_str .= $this->msg_table_row(20,$msg_date, $is_th, '#FFFFFF', 'LEFT');
		
		$ret_str .= "<TR>\n$t_str</TR>\n";
            }
		
	    $ret_str .= $this->msgTableFooter();
		
	    if((count($mailboxes) > 1) && $this->protocol =='imap') {
	        $ret_str .= "DELETE<INPUT TYPE=\"RADIO\" VALUE=\"delete_msg\" NAME=\"action\">\n";
		$ret_str .= "COPY<INPUT TYPE=\"RADIO\" VALUE=\"copy_msg\" NAME=\"action\">\n";
	        $ret_str .= "MOVE<INPUT TYPE=\"RADIO\" VALUE=\"move_msg\" CHECKED NAME=\"action\">\n";
		$ret_str .= "TO<SELECT NAME=\"new_mailbox\" SIZE=\"1\">\n";
		foreach($mailboxes as $mbox) {
		    if($mbox != $this->mailbox && (!(($mbox=='INBOX') && (empty($this->mailbox)))))
		    $ret_str .= "<OPTION VALUE=\"$mbox\">$mbox</OPTION>\n";
		
		    $ret_str .= "</SELECT>\n";
		    $ret_str .= "<INPUT TYPE=\"Submit\" VALUE=\"GO!\">\n";
		}
		
		$ret_str .= "</FORM>\n";
		$ret_str .= "<BR>\n";
		$ret_str .="<CENTER>\n";
		
		for($i = 1; $i <= $num_page; $i++) {
		    if($cur_page == $i) $ret_str .= "<STRONG>[$i]</STRONG>";
		    else $ret_str .= $this->build_url("action=list_msg&mailbox=$this->mailbox&sort=$this->sort&reverse=$order&cur_page=$i", "[$i]");
		}
	    }
	    $ret_str .="</CENTER>\n";
	    return $ret_str;
	}
   
	function listMailbox($mailbox='') 
        {      
	    $str = "";
		
	    $mailboxes = $this->get_mailbox_list($mailbox);
	    if(!$mailboxes) return false;
		
	    foreach($mailboxes as $mbox=>$unseen) {
	        if($this->protocol !='nntp' && $this->protocol !='pop3' && $mbox != 'INBOX') 
		$del_prefix = $this->buildUrl("action=delete_mailbox&del_mailbox=$mbox", "[X]", "if(!confirm('Are you sure?')) return false;");
		else $del_prefix = '';
		
		if($this->protocol == 'nntp') {
		    $str .= $this->buildUrl("action=list_msgs&mailbox=$mbox", "$mbox($unseen)") . "<BR>\n";
		}
		else {
		    $str .= $del_prefix . $this->buildUrl("action=list_mailbox&mailbox=$mbox", "$mbox($unseen)") . "<BR>\n";
		}
        }
	return $str;
    }

    function readMsg() 
    {
	
        $msg = $this->getMsg('action=download_attachment', 'action=mail_form&mode=reply');
		
	if(!$msg) return false;
	$ret_str = "<STRONG>Date: </STRONG>" . $msg["date"] . "<BR>\n";
	$ret_str .= "<STRONG>From: </STRONG>" . $msg["from"] . "<BR>\n";
	if(!empty($msg["cc"])) $ret_str .= "<STRONG>Cc: </STRONG>" . $msg["cc"] . "<BR>\n";
	if(!empty($msg["references"])) $ret_str .= "<STRONG>References: </STRONG>" . $msg["references"] . "<BR>\n";
	$ret_str .= "<STRONG>Subject: </STRONG>" . $msg["subject"] . "<BR>\n";
	
	if($this->protocol == 'nntp') $ret_str .= $this->build_url("action=mail_form&mailbox=$this->mailbox&mode=followup&msg_uid=$this->msg_uid", "[Reply to this article]");
	else $ret_str .= $this->build_url("action=mail_form&mailbox=$this->mailbox&mode=forward&msg_uid=$this->msg_uid", "[Forward this message]");
	$ret_str .= "<BR><BR>\n";
		
	$ret_str .= "<BLOCKQUOTE>" . $msg["body"] . "</BLOCKQUOTE><BR>\n";
		
	if($msg["num_parts"] > 0) {
	    $ret_str .= "<CENTER><HR WIDTH=\"90%\" SIZE=\"1\"></CENTER>\n";
	    for($i = 0; $i < count($msg["parts"]); $i++) $ret_str .= $msg["parts"][$i] . "<BR>\n";
	}
		
	return $ret_str;
    }
	
    function htmlHeader($title='', $charset='') 
    {
        $ret_str = "<HTML>\n";
	$ret_str .= "<HEAD>\n";
	if(!empty($charset)) $ret_str .= "<META HTTP-EQUIV=\"CONTENT-TYPE\" CONTENT=\"TEXT/HTML; CHARSET=$charset\">\n";
	$ret_str .= "<TITLE>$title</TITLE>\n";
	$ret_str .= "</HEAD>\n";
	$ret_str .= "<BODY>\n";
		
	return $ret_str;
    }
	
    function htmlFooter() 
    {
        $ret_str = "</BODY>\n";
	$ret_str .= "</HTML>\n";
		
	return $ret_str;
    }

}

$host = "localhost";
$protocol = "imap";
$port = 134;
$userid = "wankyu";
$userpassword = "12345";

$wmail = new myWebmail();
if(!$wmail->init($host, $protocol, $port, $userid, $userpassword)) echo $wmail->errorMsg();

if(!$wmail->start($action)) echo $wmail->errorMsg();

$wmail->end();

?>
