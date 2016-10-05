<?php

//alias.php

$hostName="moniker.wrox.com";

if (checkdnsrr($hostName, "CNAME")) {
    echo("The host $hostName has an alias name.<BR>\n");
} else {
    echo("The host $hostName does not have an alias name.<BR>\n");
}
?> 
