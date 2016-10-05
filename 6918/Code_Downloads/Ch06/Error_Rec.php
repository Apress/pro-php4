<?php

//Error_Rec.php
class connectionManager 
{
    var $connections;

    //This function opens a connection and adds it to a list of open connections
    function openConnection ($host, $user, $pass) 
    {
        //attempt to connect to a mysql database
	$mysql_link = @ mysql_connect ($host, $user, $pass);

        //place the connection id in the connections array
        if (FALSE !== $mysql_link) {
	$this->connections[] = $mysql_link;
    }

    return $mysql_link;
}
    //This function should be called when all the connections need to be closed
    function cleanup () 
    {
	foreach ($this->connections as $id)
	@ mysql_close ($id);
		
    }
}

//Instantiate the class
$myConnxnMgr = new connectionManager();
//Code uses the connectionManager class to create new connections
$connxn1 = $myConnxnMgr->openConnection("mysqldb.wrox.com","dbuser", "dbpassword");

//Do something useful with the connections during which time an error might occur

//Clean up since execution cannot continue due to the error that occurred
$myConnxnMgr->cleanup();

?>
