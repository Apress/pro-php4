<?php

require_once("DB.php");

$db = new DB('localhost', 'root', 'hillary', 'Library');
if (!$db->open()) {
    die ($db->error());
}
if (!$db->query("SELECT * FROM title")) {
    die ($db->error());
}

while ($row = $db->fetchAssoc()) {
    echo("ISBN: " . htmlspecialchars($row['ISBN']) .
    ", title: " . htmlspecialchars($row['book_title']) . "<br />");
}

$db->freeResult();
$db->close();
?>
