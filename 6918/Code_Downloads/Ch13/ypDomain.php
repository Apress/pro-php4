<?php

//ypDomain.php

$domain = yp_get_default_domain();
if ($domain != FALSE) {
    echo("The default NIS domain is $domain. <BR>");
} else {
    echo("Default domain is not available. <BR>");
}
?>
