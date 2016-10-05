<?php

// my_mail_class_test.php

include("./my_mail_class.php");

$mail = new My_Mail();

$mail->to = "someone@a.com";
$mail->from = "wankyu@whatever.com";
$mail->cc = "someoneelse@a.com,yetanotherone@a.com";
$mail->bcc = "someone@b.com,mole@a.com";
$mail->subject = "Hi there!";
$mail->body = "Just testing...";
$mail->rigorous_email_check = 1;

if($mail->send()) {
    echo("Successfully sent an email titled $mail->subject!");
} else {
    echo("Error while attempting to send an email titled $mail->subject:" . $mail->errorMsg());
}

echo("<br>");
echo(str_replace("\r\n", "<br>", $mail->viewMsg()));
?>
