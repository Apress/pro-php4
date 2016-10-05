<?php

class DB
{
    // Connection parameters 
    var $host = '';
    var $user = '';
    var $password = '';
    var $database = '';
    var $persistent = false;

    // Database connection handle 
    var $conn = NULL;

    // Query result 
    var $result = false;

    function DB($host, $user, $password, $database, $persistent = false)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->persistent = $persistent;
    }

    function open()
    {
        // Choose the appropriate connect function 
        if ($this->persistent) {
            $func = 'mysql_pconnect';
        } else {
            $func = 'mysql_connect';
        }

        // Connect to the MySQL server 
        $this->conn = $func($this->host, $this->user, $this->password);
        if (!$this->conn) {
            return false;
        }

        // Select the requested database 
        if (!@mysql_select_db($this->database, $this->conn)) {
            return false;
        }

        return true;
    }

    function close()
    {
        return (@mysql_close($this->conn));
    }

    function error()
    {
        return (mysql_error());
    }

    function query($sql = '')
    {
        $this->result = @mysql_query($sql, $this->conn);

        return ($this->result != false);
    }

    function affectedRows()
    {
        return (@mysql_affected_rows($this->conn));
    }

    function numRows()
    {
        return (@mysql_num_rows($this->result));
    }

    function fetchObject()
    {
        return (@mysql_fetch_object($this->result, MYSQL_ASSOC));
    }

    function fetchArray()
    {
        return (@mysql_fetch_array($this->result, MYSQL_NUM));
    }

    function fetchAssoc()
    {
        return (@mysql_fetch_assoc($this->result));
    }

    function freeResult()
    {
        return (@mysql_free_result($this->result));
    }
}
?>

