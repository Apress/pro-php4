<?php

$dbHostName="localhost";
$dbName = "shop";
$dbUserName = "php";
$dbPassword = "welcome";

//Sends the error page
function sendErrorPage($mesg) 
{
    header("Content-Type: text/vnd.wap.wml");
    printf("<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">");
    printf("<wml>\n");
    printf("<card id=\"errorCard\">\n");
    printf("<p>\n");
    printf("%s", $mesg);
    printf("</p>\n");
    printf("</card>\n");
    printf("</wml>\n");
}

// Get the sessionId string
function getSessionIdString() 
{
    return "session_name=".session_id();
}

// Generate option element 
function generateOptionElement($href, $displayText) 
{
    printf("<option>\n");
    printf("<onevent type=\"onpick\">\n");
    printf("<go href=\"%s\"/>\n",$href);
    printf("</onevent>\n");
    printf("%s\n", $displayText);
    printf("</option>\n");
}

// Returns Date
function getDateString() 
{
    $dateArray = getdate();
    return $dateArray["year"] . "-" . $dateArray["mon"] . 
        "-" . $dateArray["mday"];
}

// Format the date
function convertDateFromMysqlFormat($dateStr) 
{
    list ($year, $month, $day) = split("-", $dateStr);
    return $month . "/" . $day . "/" . $year;
}

// Format the date
function convertDateToMysqlFormat($dateStr) 
{
    list ($month, $day, $year) = split("/", $dateStr);
    return $year . "-" . $month . "-" . $day;
}

function getDBConnection() 
{
    global $dbHostName, $dbUserName, $dbPassword, $dbName;

    // Get a persistent database connection
    if (!($link = mysql_pconnect($dbHostName, $dbUserName, $dbPassword))) {
        return new FunctionResult("InternalError: Could not open database connection", null);
    }

    // select mysql database
    if (!mysql_select_db($dbName, $link)) {
        return new FunctionResult("InternalError: Could not select database ", null);
    }
    return new FunctionResult(null, $link);
}

class Function_Result 
{
    var $errorMessage;
    var $returnValue;

    function Function_Result($errMessage, $retValue) 
    {
        $this->errorMessage = $errMessage;
	$this->returnValue =  $retValue;
    }

}

function open($save_path, $session_name) 
{
    return true;
}	

function close() 
{
    return true;
}	

function read($id) 
{
    global $dbHostName, $dbUserName, $dbPassword, $dbName;
    // Get a persistent Connection
    if (!($link = mysql_pconnect($dbHostName, $dbUserName, $dbPassword))) {
        return null;
    }

    // select mysql database
    if (!mysql_select_db($dbName)) {
        return null;
    }

    // Select Statement
    $selectStmt = "Select data from Session where id = '" . $id . "'";

    // Execute the query
    if (!($result = mysql_query($selectStmt, $link))) {
        return null;
    }
    if (($row = mysql_fetch_array($result, MYSQL_NUM))) {
        $data = $row[0];
    } else {
        $data = null;
    }
    mysql_free_result($result);
    return $data;
}	

function write($id, $data) 
{
    global $dbHostName, $dbUserName, $dbPassword, $dbName;
    // Get a persistent Connection
    if (!($link = mysql_pconnect($dbHostName, $dbUserName, $dbPassword))) {
        return false;
    }

    // select mysql database
    if (!mysql_select_db($dbName)) {
        return false;
    }

    // Replace Statement
    $replaceStmt = "replace into Session(id, data) values ('$id', '$data')";

    // Execute the query
    if (!($result = mysql_query($replaceStmt, $link))) {
        return false;
    }
        return mysql_affected_rows($link);
}	

function destroy($id) 
{
    global $dbHostName, $dbUserName, $dbPassword, $dbName;
    // Get a persistent Connection
    if (!($link = mysql_pconnect($dbHostName, $dbUserName, $dbPassword))) {
        return false;
    }

    // select mysql database
    if (!mysql_select_db($dbName)) {
        return false;
    }

    // Replace Statement
        $deleteStmt = "delete from Session where id = '$id'";

    // Execute the query
    if (!($result = mysql_query($deleteStmt, $link))) {
        return false;
    }
    return mysql_affected_rows($link);
}	

function gc($maxlifetime) 
{
    global $dbHostName, $dbUserName, $dbPassword, $dbName;
    // Get a persistent Connection
    if (!($link = mysql_pconnect($dbHostName, $dbUserName, $dbPassword))) {
        return false;
    }

    // select mysql database
    if (!mysql_select_db($dbName)) {
        return false;
    }

    // Delete Statement
    $deleteStmt = "delete from Session where CURRENT_TIMESTAMP < (lastAccessed + ". $maxlifetime . ")";

    // Execute the query
    if (!($result = mysql_query($deleteStmt, $link))) {
        return false;
    }
    return mysql_affected_rows($link);
}	

function setSessionHandlers() 
{
    session_set_save_handler("open", "close", "read", "write", 
						"destroy", "gc");
}

function checkSessionAuthenticated() 
{
    global $isAuthenticated;
    session_start();
    if (session_is_registered("isAuthenticated") && $isAuthenticated) {
        return true;
    } else {
        sendErrorPage("Unauthenticated Session");
        exit;
    }
}
?>
