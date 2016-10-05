<?php

// mail_test.php

$mail_to = "someone@a.com";
$mail_from = "spammer@b.com";
$mail_reply_to = "spammer2@b.com";
$mail_cc = "someoneelse@a.com,yetanotherone@a.com";
$mail_bcc = "mole@a.com";

$mail_headers = "From: $mail_from\r\nReply-to: $mail_reply_to\r\nCc: $mail_cc\r\nBcc: $mail_bcc";

$mail_subject = "I know a secret to your success!";
$mail_body = "Mail me back right now!";

if(mail($mail_to, $mail_subject, $mail_body, $mail_headers))
{
	echo("Successfully sent an email titled '$mail_subject'!");
} else {
	echo("An error occurred while attempting to send an email titled '$mail_subject'!");
}
?>
