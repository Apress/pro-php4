<?php

//multihostame.php

$hostName = "multi.wrox.com";
$ipAddresses = gethostbynamel($hostName);

echo("The IP addresses of $hostName are:<BR>\n");

for ($i = 0; $i < count($ipAddresses); $i++) {
    echo("$ipAddresses[i] <BR>\n");
}
?>
