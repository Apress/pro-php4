<?php

$xslData = file("travel.xsl", "r");
$xmlData = file("travel.xml", "r");

$xslStr = implode("", $xslData);
$xmlStr = implode("", $xmlData);

if (xslt_process($xslStr, $xmlStr, $result)) {
    echo($result);
} else {
    echo("There is an error in the XSL transformation...\n");
    echo("\tError number: " . xslt_errno() . "\n");
    echo("\tError string: " . xslt_error() . "\n");
    exit;
}
?>
