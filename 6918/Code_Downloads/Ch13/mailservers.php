<?php

//mailservers.php

$domain = "somedomain.com";
getmxrr($domain, $mailXchangers, $prefs );

echo("List of mail exchangers for $domain: <BR>\n");

for ($i = 0; $i < count($mailXchangers); ++$i) {
    echo("$mailXchangers[$i] = $prefs[$i] <BR>\n");
}
?>
