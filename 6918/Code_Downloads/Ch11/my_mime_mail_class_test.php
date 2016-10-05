<?php

// my_mime_mail_class_test.php

include("./my_mime_mail_class.php");

$mail = new my_mime_mail();

$mail->to = 'wankyu@whatever.com';
$mail->from = 'yonsuk@whoelse.com';
$mail->subject = "My picture!";
$mail->body = "Here goes my picture! Send me yours!";
$mail->files[0]["file"] = '/home/yonsuk/yonsuk.gif';
$mail->files[0]["filename"] = 'yonsuk.gif';
$mail->files[0]["filetype"] = 'image/gif';

if($mail->send()) {
    echo("Successfully sent an email titled '$mail->subject'!");
} else {
    echo($mail->errorMsg());
}

echo ("<br>");
echo (str_replace("\r\n", "<br>", $mail->viewMsg()));
?>
