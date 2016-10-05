<?php

require_once("DB.php");

$db = new DB('localhost', 'root', 'hillary', 'Library');
if (!$db->open()) {
    die ($db->error());
}
if (!$db->query("REPLACE INTO title VALUES ('1861003730', 'New Title')")) {
    die ($db->error());
}
echo("Affected rows: " . $db->affectedRows() . "<br />");

$db->freeResult();
$db->close();
?>
