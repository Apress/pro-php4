<?php

//portbyname.php

$protocol = "smtp";
$portNum = getservbyname($protocol, "tcp");

echo("The port number of the $protocol service is $portNum");
?>
