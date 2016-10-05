<?php

define ('LOCAL',       1 << 0);         // Define helper constants
define ('REMOTE',      1 << 1);
define ('TO_LOCAL',    LOCAL);
define ('TO_REMOTE',   REMOTE);
define ('FROM_LOCAL',  LOCAL << 2);
define ('FROM_REMOTE', REMOTE << 2);

class Ftp_Wrapper 
{
    var $connection,                      // A list of the open ftp connections
    $tmp_dir = 'C:/',                     // The system temp directory
    $mode;                                // The transfer mode (ASCII or BINARY)
    
    function ftp_wrapper () 
    {                                     // Class constructor
        $this->connection = array ();                                 // Initialize the connections array
        register_shutdown_function ( array ($this, '_destructor'));   // Call class destructor at script end
        $this->mode = FTP_BINARY;    // Default transfer mode is BINARY
    }
    
    function mode ($mode = NULL) 
    {                                // Set the transfer mode
        if (NULL !== $mode)              // If needed, set the mode
            $this->mode = $mode;
        return $this->mode;              // Return the current setting
    }

    function copy (          // Copy files between servers via ftp
               $from,    // Source file/location
               $to)      // Destination file/location                    
    {                                              
        $from = $this->_parse ($from);  // Split the locations into separate
        $to   = $this->_parse ($to);    // user, pass, host, port & path values
        if ( ! $from || ! $to )         // Exit if _parse() failed
            return FALSE;

        if ( ! $this->_connect ($from))      // Connect to the source location
            return FALSE;                    // Exit if connection fails

        if ( ! $this->_connect ($to) )       // Connect to the source location
            return FALSE;                    // Exit if connection fails
    
        switch (              // Determine how to handle the copy
            $from['type'] << 2    // Make from type match a FROM_* constant
            | $to['type'])        // Use 'or' to make a bit flag  
        {
            case (FROM_LOCAL | TO_LOCAL):       // Copy local files
                return copy (                       // Local copies can use copy()
                    $from['path'],
                    $to['path']
                    );
                break;

            case (FROM_LOCAL | TO_REMOTE):      // Copy a local file to a remote server
                return copy (
                    $this->_conn($to),
                    $to['path'],
                    $from['path'],  
                    $this->mode ()
                );
                break;
  
            case (FROM_REMOTE | TO_LOCAL):      // Copy a remote file to local path
                $temp = $to['path'];
        
                if (@ is_dir ($temp) ) {            // Convert directory to full file path
                    if (substr ($temp, 0, -1) != '/')
                        $temp .= '/';
                    $temp .= basename ($from['path']);
                }

                $fp = fopen ($temp, 'w');           // Local file pointer for ftp_fget()
        
                if (! $fp) {                        // Exit if file pointer can't be opened
                    user_error ("File '{$to['path']}' could not be opened for writing");
                    return FALSE;
                }

                $return_val = ftp_fget (                  // Copy file
                   $this->_conn($from),
                   $fp,
                   $from['path'],
                  $this->mode ()
                );
  
                fclose ($fp);                             // Close file pointer

                return $return_val;
                break;

            case (FROM_REMOTE | TO_REMOTE):               //Copy a file between two hosts
                $tmp = $this->tmp_dir                         // Make a temp file name to store file
                    . '/ftpcp_'
                    . md5 ($from['safe']
                    . $to['safe']);

                touch ($tmp);                          // Try to create the file
                chmod ($tmp, 0700);                    // Restrict permissions

                $result  = $this->copy (               // Copy remote source file to temp file
                    $from['safe'],
                    $tmp
                );

                $result2 = $this->copy (               // Copy local temp file to remote file
                    $tmp,
                    $to['safe']
                );

                unlink ($tmp);                         // Delete temp file
                return (bool) (                        // If transactions succeed, return TRUE
                    $result &&
                    $result2
                );
                break;

            default:                                                     // If none of the above bit flags matches
                user_error ("No bit flags matched. This should never happen!");
                return FALSE;                                            // Exit
                break;
        }
    }


    function &_conn ($info)         // This class caches all FTP connections
    {      
        $md5 = md5 (               // _conn() returns any cached connections
            $info['host'] .        // that exist for a given combination of
            $info['pass'] .        // host, password, port and user
            $info['port'] .
            $info['user']
        );
        return $this->connection[$md5];
    }

    function _parse ($info)                  // Parse a location into its components
    {
        $chars = count_chars ($info);
        if (
          $chars[ord (':')] < 2                          // < 2 colons and 1 @ symbol indicate
          && 0 == $chars[ord ('@')]) {                   //$info contains no host/user/pass data
            return array (
                'safe' => $info,                         // Store original $info for later use
                'user' => FALSE,
                'pass' => FALSE,
                'host' => FALSE,
                'port' => FALSE,
                'path' => $info
            );
        }

        if ($chars[ord ('@')] > 1) {                      // Multiple @ symbols could break _parse
            user_error (
               "The network and path information could not be parsed " .
               "from the location. Are you sure you meant '{$info}'?"
           );
           return FALSE;                                  // Exit to prevent any problems
        }

        $with_port = preg_match (                         // Try to get user/pass/host/port/path
            '/^([^:]+):(.+)@(.+):([0-9]+):([^:]+)$/',
            $info,
            $match
            );

        if ($with_port) {                     // If we could get all data
            return array (                    // Return it in an associative array
            'safe' => $info,
            'type' => REMOTE,
            'user' => $match[1],
            'pass' => $match[2],
            'host' => $match[3],
            'port' => $match[4],
            'path' => $match[5]
            );
        }

        $without_port = preg_match (  // Try to get user/pass/host/path data
            '/^([^:]+):(.+)@(.+):([^:]+)$/',
            $info,
            $match
        );
        if ($without_port) {
            return array (
            'safe' => $info,
            'type' => REMOTE,
            'user' => $match[1],
            'pass' => $match[2],
            'host' => $match[3],
            'port' => 21,             // Set default port
            'path' => $match[4]
            );
        }
        user_error (                    // Error should not display $info
            "Host, authentication and path data could not be parsed"
        );

        return FALSE;
    }

    function _connect ($info)                  // Attempt to connect to a location
    {                                          // The location to connect to
        $conn =& $this->_conn ($info);         // Try to fetch cached connection
        if (isset ($conn))                     // If a cached connection exists,
            return $conn;                      // return the cached connection
            foreach ($info as $k => $v) {      
                ${$k} =& $info[$k];            // Convert array keys into local vars
            }
        }

        if (! $host) {                                                   // Skip local locations
            return TRUE;
        }

        $fh = ftp_connect ($host, $port);                                // Connect to FTP server
 
        if ( ! $fh) {                                                    // Handle failed connections
            user_error ("Could not connect to host '$host:$port'");
            return FALSE;
        }

        $logged_in = ftp_login ($fh,$user,$pass);                        // Attempt to authenticate

        if ( ! $logged_in ) {                                            // Handle failed authentication
            user_error ("Could not authenticate on host '$host:$port'.\n");
            return FALSE;
        }

        $conn = $fh;                                                      // Cache successful connections
        return TRUE;
    }

    function _destructor ()                                             // Clean up cached FTP connections   
    {                                           
        foreach ($this->connection as $k => $v)
            if (is_resource ($v))
                ftp_quit ($this->connection[$k]);
        $this = NULL;
    }
}
?>
