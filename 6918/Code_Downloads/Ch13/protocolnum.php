<?php

//protocolnum.php

$protocolName = "http";
$protocolNum = getprotobyname($protocolName);

echo("The protocol number for $protocolName is $protocolNum");
?>
