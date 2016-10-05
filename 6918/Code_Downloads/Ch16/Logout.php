<?php

include_once ("Common.php");
include_once ("User.php");

setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );
checkSessionAuthenticated();
session_destroy();
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card >
    <p>
      Thanks <i><?php echo $user->getUserId() ?></i> for using Shopping Cart Application
    </p>
  </card>
</wml>
