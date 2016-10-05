<?php

//hostaddress.php

$ipAddress = "127.0.0.1";
$hostName = gethostbyaddr($ipAddress);

echo("The host name corresponding to the IP address $ipAddress is $hostName");
?>
