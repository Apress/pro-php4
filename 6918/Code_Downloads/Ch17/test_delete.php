<?php

require_once("DB.php");
$db = new DB('localhost', 'root', 'hillary', 'Library');
if (!$db->open()) {
    die ($db->error());
}
if (!$db->query("DELETE FROM author WHERE auth_name='Jon Paris'")) {
    die ($db->error());
}
echo("Affected rows: " . $db->affectedRows() . "<br />");

$db->freeResult();
$db->close();
?>
