<?php

// my_nntp_class_test.php

include("./my_nntp_class.php");

$nntp = new My_Nntp();
$nntp->nntp_host = "news.php.net";

$nntp->from = "wankyu@whatever.com";
$nntp->subject = "Wrox rocks! - a test article.";
$nntp->body = "Posting a test article with an attachment"; 
$nntp->newsgroups = "php.test";
$nntp->files[0]["file"] = '/home/wankyu/mypicture.gif';
$nntp->files[0]["filename"] = 'mypicture.gif';
$nntp->files[0]["filetype"] = 'image/gif';

if($nntp->send()) {
    echo("An article titled '$nntp->subject' has been successfully posted on the following newsgroup(s): $nntp->newsgroups");
}

echo($nntp->errorMsg());
echo("<br>");
echo(eregi_replace("\r\n", "<BR>", $nntp->viewMsg()));
?>
