<?php

require_once ("DB.php");
$db = new DB('localhost', 'root', 'hillary', 'Library');
?>

<?php

require_once("DB.php");
$db = new DB('localhost', 'root', 'hillary', 'Library');

if (!$db->open()) {
    die ($db->error());
}
if (!$db->close()) {
    die ($db->error());
}
?>


