<?php

include("priv.classes.inc");
include("DB.php");

$sql = new DB();

$user = new User('dilipt');

$user->addPrivilege(3);

$user->removePrivilege(3);

$user = new User();

$user->setUsername('matthewm');
$user->setFullname('Matthew Moodie');

$user->create();

$user->update();

$user->delete();

$priv = new Privilege();

echo("<br>");
echo("Before set: ");

print_r($priv->getPriv_id());

$priv->setPriv_id(1);
$priv->setDescription('allow all');

echo("<br>");
echo("After set: ");
print_r($priv->getPriv_id());

$priv->create();

$priv->update();

$priv->delete();
?>