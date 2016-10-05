<?php

set_time_limit(0);
if (getenv ('SERVER_ADMIN') ) {      // If SERVER_ADMIN is set, use it
    define (                         // Constant for default FTP password
        'SERVER_ADMIN',
        getenv ('SERVER_ADMIN')
    );
} else {                             // Build a sensible default value
    define (
        'SERVER_ADMIN',
        'root@'  . getenv ('SERVER_NAME')
    );
}


class Ftp_Web_Client 
{
    var $conn,                     // FTP connection for the class instance
        $cwd,                      // FTP server's current working directory
        $max_upload_size,          // The max size of upload allowed
        $mode,                     // The transfer mode (ASCII or BINARY)
        $systype,                  // The type of the FTP server
        $tmp_dir = '/tmp';         // The system temp directory


    function Ftp_Web_Client (           // Class constructor
        $host,                          // Host to connect to
        $user = 'anonymous',            // User name to login with
        $pass = SERVER_ADMIN,           // Password to login with
        $port = 21                      // Port to connect to
        ) 
    {
        $fh = ftp_connect (             // Connect to FTP server
            $host,
            $port
        );

        if ( ! $fh) {               // Handle failed connections
            user_error ("Could not connect to host '$host:$port'");
            return FALSE;
        }

        $logged_in = ftp_login (    // Attempt to authenticate
            $fh,
            $user,
            $pass
        );

        if ( ! $logged_in ) {                                               // Handle failed authentication
            user_error ("Could not authenticate on host '$host:$port'.\n");
            return FALSE;
        }

        $this->conn = $fh;                                       // Store successful connection

        register_shutdown_function (create_function ('', "ftp_quit ($fh);") );  // Close FTP connection at script end
            
   

        $this->cwd = ftp_pwd ($this->conn);               // Get current working dir on FTP server

        $this->max_upload_size = 1024 * 1024;             // Set maximum size for uploaded files 1 megabyte
                        
        $this->mode = FTP_BINARY;                         // Default tranfer mode is BINARY
            
        $this->systype = ftp_systype ($this->conn);   //Get the system type of the FTP server
            
    }

    function cd ($dir )                                     // Attempt to cd into $dir on FTP server
    {          
        $return = ftp_chdir ($this->conn, $dir);

        if ($return) {                // Update $this->cwd info
            $this->cwd = ftp_pwd ($this->conn);
        } else {
        user_error ("Could not cd into directory '$dir'.");
        }

        return $return;
    }

    function ls ($directory = '.')                // Fetch directory listing for $directory
    {     
        $temp = ftp_rawlist (                     // Grab raw directory listing
        $this->conn,
        $directory
        );

        if (FALSE === $temp) {       // Exit if we can't get a listing
            user_error ("The directory listings could not be retrieved.");
            return array ();
        }

        switch ($this->systype) {       // Parse raw dir listings into fields
        case 'Windows_NT':              // Handle WinNT FTP servers
            $re = '/^'.                 
            '([0-9-]+\s+'.              // Get last mod date
            '\d\d:\d\d..)\s+'.          // Get last mod time
            '(\S+)\s+'.                 //Get size or dir info
            '(.+)$/';                   // Get file/dir name
            break;

        case 'UNIX':                    // Handle UNIX FTP servers
            default:                    // Case UNIX falls through to default
            $re = '/' .                 // Regex to parse common server dir info
            '^(.)' .                    // Get entry type info (file/dir/etc.)
            '(\S+)\s+' .                  // Get permissions info
            '\S+\s+' .                  // Find, but don't capture hard link info
            '(\S+)\s+' .                // Get owner name
            '(\S+)\s+' .                // Get group name
            '(\S+)\s+' .                // Get size
            '(\S+\s+\S+\s+\S+\s+)'.     // Get file/dir last mod time
            '(.+)$/';                   // Get file/dir name
            break;
        }

        //  WARNING
        //The above regular expressions will not parse all styles of directory
        //information listings. You may need to tweak the regexes to deal with
        //certain FTP servers.
    

        $type = array (                 // Map short identifiers to full names
            '-' => 'file',
            'd' => 'directory'
        );

        $listings = array ();


        foreach ($temp as $entry) {      //Loop through raw listings
            $match = preg_match (        // Try to parse raw data into fields
                $re,
                $entry,
                $matches
            );


            if (! $match) {             // If we could not parse the listings
                user_error ("The directory listings could not be parsed.");
                return array ();
            }

            switch ($this->systype) {    // Give fields sensible names
                case 'Windows_NT':       // Handle WinNT-style dir listings
                    $listings[] = array 
                    (
                        'type' => is_numeric($matches[2]) ? 'file' :  'directory',
                        'permissions' => NULL,
                        'owner'       => NULL,
                        'group'       => NULL,
                        'size'        => (int) $matches[2],
                        'last mod'    => $matches[1],
                        'name'        => $matches[3]
                    );
                    break;
                case 'UNIX':                       // Handle UNIX FTP-style dir listings
                    default:                       // Case UNIX falls through to default
                    $listings[] = array (          // Put parsed data into readable format
                        'type'        => $type[$matches[1]],
                        'permissions' => $matches[2],
                        'owner'       => $matches[3],
                        'group'       => $matches[4],
                        'size'        => $matches[5],
                        'last mod'    => $matches[6],
                        'name'        => $matches[7]
                    );
                    break;
            }
        }

        return $listings;
    }

    function get ($file)                       // Download a file     
    {                   
        $tmp_name = $this->tmp_dir             // Create a temp file name
                    .  '/ftp_web_client_'
                    .  md5 ($file . microtime ());

        $result = ftp_get (                     // True to download file to temp file
            $this->conn,
            $tmp_name,
            $this->cwd . "/$file",
            $this->mode
        );


    if ($result) {                                                       // Pass downloaded file to the user
        header ("Content-Type: application/octet-stream");               // Send out appropriate TCP headers
        header ('Content-Disposition: inline; ' . 'filename=' . urlencode ($file));  // Set name of file with header
        readfile ($tmp_name);
        unlink ($tmp_name);
        exit ();                                                         // Prevent rest of page from showing
    }

        $clean = htmlentities($file);                                   // Download failed - warn user
        user_error("Could not download file '$clean'.");
        return FALSE;
    }

    function put(                           // Upload a file sent by user
        $name,                              // Name to give uploaded file
        $tmp,                               // Temp file storing uploaded file
        $size                               // Size of the uploaded file
        ) 
    {
        if($size > $this->max_upload_size) {                                // Skip large file uploads
            $kb = $this->max_upload_size / 1024;
            user_error ('Uploaded files must be less than ' .$kb . 'kb in size.');
            return FALSE;
        }

        $result = ftp_put (                 //Attempt to upload file
            $this->conn,
            $name,
            $tmp,
            $this->mode
        );

        unlink ($tmp);                      // Destroy the temp file
        if (! $result) {                    // Upload failed - warn user
            $clean = htmlentities ($name);
            user_error ("Could not upload file '$clean'.");
        }

        return $result;
        }
    }


$ftp = new ftp_web_client ('ftp.wrox.com'); 	

if ( 'Change to Selected Directory' == $_POST ['action'] ) {
    $ftp->cd ($_POST ['dir']);
} else if ( isset ($_POST ['cwd']) ) {
    $ftp->cd ($_POST ['cwd']);
}

if ( 'Download Selected File' == $_POST ['action'] ) {
    if ( isset ($_POST ['selected_file']) ) {
        $ftp->get ($_POST ['selected_file']);
    } else {
        user_error ("Please select a file to download!");
    }
}

if ( 'Upload File' == $_POST ['action'] ) {
    if ( isset ($_FILES['upload']) ) {
        $ftp->put (
            $_FILES ['upload']['name'],
            $_FILES ['upload']['tmp_name'],
            $_FILES ['upload']['size']
        );

    } else {
        $error[] = "Please browse for a file to upload!";
    }
}

$directory = array ();
$file = array ();
foreach ($ftp->ls($ftp->cwd) as $entry) {
    switch ($entry['type']) {
    case 'directory':
        $directory[] = $entry['name'];
        break;
    
    default:                             // Handle files and symlinks
        $file[$entry['name']] =          // with the default case
            sprintf ("%s (%0.2f kb)",
                $entry['name'],
                $entry['size'] / 1024
            );
        break;
    }
}
?>

<form action="<?php echo getenv ('SCRIPT_NAME'); ?>" enctype="multipart/form-data" method="POST">
  <input type="hidden" name="cwd" value="<?php echo htmlentities (stripslashes ($ftp->cwd)); ?>" />
  <input type="hidden" name="max_file_size" value="<?php echo $ftp->max_file_size; ?>" />

  <p><b>Current Working Directory:</b> <?php echo $ftp->cwd ?></p>
  <p>
    <select name="dir">
      <?php
      if ('/' == $ftp->cwd) {
          echo("<option>/</option>");
      } else {
          echo("<option value=\"{$ftp->cwd}\"> . ({$ftp->cwd})</option>\n",
              "<option value=\"{$ftp->cwd}/..\"> .. </option>\n");
      }

      foreach ($directory as $name => $entry) {
          printf (
              '<option value="%s">%s</option>'."\n",
              "{$ftp->cwd}/$name",
              $name
          );
      }
      ?>
    </select>
    <input type="submit" name="action" value="Change to Selected Directory" />
  </p>

  <p>
    <select name="selected_file" size="12">
      <?php
      foreach ($file as $name => $entry) {
          echo("<option value=\"$name\">$entry</option>\n");
      }
      ?>
    </select><br />
    <input type="submit" name="action" value="Download Selected File" /><br /><br />
    <input type="file" name="upload" />
    <input type="submit" name="action" value="Upload File" />
  </p>
</form>
