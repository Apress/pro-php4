<?php

$HOST = "localhost";
$DBNAME="sessions";
$USER = "sessionmanager";
$PASS = "sessionmanager";

session_start();

//The name of the MySQL handler/pointer we will be using, and the lifetime of the session as set by php.ini

$HANDLER = "";
$LIFETIME = get_cfg_var("session.gc_maxlifetime");

function sessionOpen($save_path, $session_name) 
{
    global $HOST, $DBNAME, $USER, $PASS, $HANDLER;
    if (!$HANDLER = mysql_pconnect($HOST, $USER, $PASS)) {
        echo("<li>Can't connect to $HOST as $USER");
        echo("<li>MySQL Error: ", mysql_error());
        die;
    }

    if (! mysql_select_db($DBNAME, $HANDLER)) {
        echo("<li>We were unable to select database $DBNAME");
        die;
    }

    return true;
}

 
function sessionRead ($session_key) 
{ 
    global $session; 
    $session_key = addslashes($session_key); 

    $session_session_value = mysql_query("SELECT session_value      
    FROM sessions WHERE session_key = '$session_key'") or  
    die(db_error_message());

    if (mysql_numrows($session_session_value) == 1) 
    { 
        return mysql_result($session_session_value, 0); 
    } else { 
        return false; 
    } 
}
 
function sessionWrite ($session_key, $val) 
{ 
    global $session; 
    $session_key = addslashes($session_key); 
    $val = addslashes($val); 

    $session = mysql_result(mysql_query("SELECT COUNT(*) FROM sessions 
    WHERE session_key = '$session_key'"), 0); 

    if ($session == 0) { 
        $return = mysql_query("INSERT INTO sessions (session_key, session_expire,
        session_value) VALUES ('$session_key', UNIX_TIMESTAMP(NOW()), '$val')") 
        or die(db_error_message()); 
    } else { 
        $return = mysql_query("UPDATE sessions SET session_value = '$val',
        session_expire = UNIX_TIMESTAMP(NOW()) WHERE session_key = '$session_key'")
        or die(db_error_message()); 

        if (mysql_affected_rows() < 0) { 
            echo("We were unable to update session session_value for session
            $session_key"); 
        } 
    } 

    return $return; 
}

function sessionDestroyer ($session_key) 
{ 
    global $session; 
    
    $session_key = addslashes($session_key); 

    $return = mysql_query("DELETE FROM sessions WHERE session_key =
    '$session_key'") or die(db_error_message()); 
    return $return; 
}

function sessionGc ($maxlifetime) 
{ 
    global $session; 
    
    $expirationTime = time() - $maxlifetime; 

    $return = mysql_query("DELETE FROM sessions WHERE session_expire <
    $expirationTime") or die(db_error_message()); 
    return $return; 
} 

session_set_save_handler ( 
'sessionOpen', 
'sessionClose', 
'sessionRead', 
'sessionWrite', 
'sessionDestroyer', 
'sessionGc' 
); 
?>

