<?php

// test.userpriv.php

require_once("DB.php");
require_once("priv.classes.inc");

$sql = new DB("localhost", "", "", "userprivilege");
$sql->open();

// Test creating a user:
$oUser = new User();
$oUser->setUsername("scollo");
$oUser->setFullname("Christopher Scollo");
$oUser->create();

// Test creating a privilege:
$oPriv = new Privilege();
$oPriv->setDescription("stay out late");
$oPriv->create();

// Test assigning a privilege:
$oUser->addPrivilege($oPriv->getPriv_id());

// Test populating objects from the database:
unset($oUser);
unset($oPriv);
$oUser = new User("scollo");
$oPriv = new Privilege(1);

if ($oUser->hasPrivilege($oPriv->getPriv_id())) {
    echo($oUser->getFullname() . " may " . $oPriv->getDescription() 
                                         . "<br />");
} else {
    echo($oUser->getFullname() . " may not " . $oPriv->getDescription() 
                                             . "<br />");
}

// Test deleting the objects:
$oUser->delete();
$oPriv->delete();
?>







