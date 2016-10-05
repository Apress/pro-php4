<?php

// imap_open_test.php

$mailbox = "{whatever.com:143}INBOX";
$userid = "wankyu";
$userpassword = "12345";
$stream = imap_open($mailbox, $userid, $userpassword);

if(!$stream) die("Error opening a stream to the IMAP server! " .  imap_last_error());

echo("Successfully opened a stream to INBOX!");
if(!imap_close($stream)) die("Error closing the stream!");
?>
