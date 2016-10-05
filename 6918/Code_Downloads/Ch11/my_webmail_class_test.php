<?php

// my_webmail_class_test.php

include("./my_webmail_class.php");
$wmail = new My_Webmail();
if(!$wmail->start($action)) echo $wmail->errorMsg();
?>
