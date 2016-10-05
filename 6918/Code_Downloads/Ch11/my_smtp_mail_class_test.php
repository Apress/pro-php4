<?php

// my_smtp_mail_class_test.php

include("./my_smtp_mail_class.php");

$mail = new my_smtp_mail();
$mail->smtp_host = 'whatever.com';

$mail->to = "someone@a.com";
$mail->from = "wankyu@whatever.com";
$mail->cc = "someoneelse@a.com,yetanotherone@a.com";
$mail->bcc = "someone@b.com,mole@a.com";
$mail->subject = "Hi there!";
$mail->body = "Just testing...";
$mail->rigorous_email_check = 1;

if($mail->send()) {
    echo("Successfully sent an email titled $mail->subject!");
} else die("Error while attempting to send an email titled $mail->subject:" . $mail->error_msg());

echo("<br>");
echo(str_replace("\r\n", "<br>", $mail->view_msg()));
?>
