<?php

require("DB.php");
require("priv.classes.inc");

$sql = new DB("localhost", "", "", "userprivilege");
$sql->open();

   /***************************************************************************

   userprivilege.php

   Christopher Scollo   scollo@taurix.com

   ***************************************************************************

   Function Synopses:

   void displayUserSelection (arr aUsers) 
      // Generate page for selecting a user

   void displayPrivileges (obj oUser, arr aPrivileges)
      // Generate page for privilege assignment

   array getUsers()
      // Returns an array of all users

   array getPrivileges()
      // Returns an array of all privileges

   bool validate ()
      // Validate form data and set the global user object

   bool save ()
      // Save the current settings

   void main (str sAction)
      // Flow control

   ***************************************************************************

   Function Declarations:

   ***************************************************************************/

function displayUserSelection($aUsers) 
{
    // Generate page for selecting a user

    echo("<?xml version=\"1.0\"?>\n");
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
                          "DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
      <head>
        <title>User Privilege</title>
      </head>
      <body>
        <form method="post" action="userprivilege.php">
          <select name="username">
            <?php
            foreach ($aUsers as $sUsername => $sFullname) {
                echo(
                    "<option value=\"$sUsername\">" .
                    stripslashes(htmlspecialchars($sFullname)) .
                    "</option>\n"
                );
            }
            ?>
          </select>
          <br />
          <br />
          <input type="submit" name="action" value="View Privileges" />
          <input type="submit" name="action" value="Exit" />
        </form>
      </body>
    </html>
<?php
} // end displayUserSelection()

   /***************************************************************************/

function displayPrivileges($oUser, $aPrivileges) 
{
    // Generate page for privilege assignment

    echo("<?xml version=\"1.0\"?>\n");
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
                          "DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
      <head>
        <title>User Privilege</title>
      </head>
      <body>
        <h1>
          Privileges for User
          <?php 
          echo(stripslashes(htmlspecialchars($oUser->getFullname()))); ?>
        </h1>
        <form method="post" action="userprivilege.php">
          <input type="hidden" name="username" 
                 value="<?php echo($oUser->getUsername()); ?>" />
          <?php
          foreach ($aPrivileges as $iPrivID => $sDescription) {
              echo(
                  "<input type=\"checkbox\" name=\"priv_id[]\" 
                          value=\"$iPrivID\"" .
                  // Check checkbox if user has this privilege
                  ($oUser->hasPrivilege($iPrivID) ? " 
                  checked=\"checked\"" : "") .
                  " />" . stripslashes(htmlspecialchars($sDescription)) .
                  "<br />\n"
              );
          }
          ?>
          <br />
          <br />
          <input type="submit" name="action" value="Save" />
          <input type="submit" name="action" value="Cancel" />
        </form>
      </body>
    </html>
<?php
} // end displayPrivileges()
   /***************************************************************************/

function getUsers() 
{
    // Returns an array of all users

    global $sql;

    $aRet = array();

    $sql->query("SELECT username, fullname FROM User");

    while ($row = $sql->fetchObject()) {
        $aRet[$row->username] = $row->fullname;
    }
    return $aRet;
} // end getUsers()
   /***************************************************************************/

function getPrivileges() 
{
    // Returns an array of all privileges

    global $sql;

    $aRet = array();

    $sql->query("SELECT * FROM Privilege ORDER BY priv_id");

    while ($row = $sql->fetchObject()) {
        $aRet[(int)$row->priv_id] = $row->description;
    }
    return $aRet;
} // end getPrivileges()

   /***************************************************************************/

function validate() 
{
    // Validate form data and set the global user object

    global $priv_id, $username, $oUser;

    if ($username) {
        // Make sure it's a valid username:
        $oUser = new User(addslashes($username));
        if (!$oUser->userExists) return 0;

        if ($priv_id) {
            if (is_array($priv_id)) {
                foreach ($priv_id as $iPrivID) {
                    // Make sure it's a valid privilege:
                    $oPriv = new Privilege((int)$iPrivID);
                    if (!$oPriv->privilegeExists) return 0;
                }
            } else {
                return 0;
            }
        } else {
            $priv_id = array();
        }
    }
    return 1;
} // end validate()
   /***************************************************************************/

function save() 
{
    // Save the current settings

    global $priv_id, $oUser, $sql;

    $bRet = 1;

    // Remove all privileges from user
    $sUsername = $oUser->getUsername();
    $sql->query("DELETE FROM UserPrivilege WHERE username='$sUsername'");

    // Now add back the selected privileges
    foreach ($priv_id as $iPrivID) {
        if (!$oUser->addPrivilege($iPrivID)) $bRet = 0;
    }

    return $bRet;
} // end save()

  /***************************************************************************/

function main($sAction) 
{
    // Flow control

    if (!validate()) return;

    switch ($sAction) {
    case "":
        // Fall through
        
    case "Cancel":
        displayUserSelection(getUsers());
        break;

    case "Exit":
        header("Location: index.php");
        exit;

    case "View Privileges":
        if ($oUser = $GLOBALS["oUser"]) {
            displayPrivileges($oUser, getPrivileges());
        }
        break;

    case "Save":
        save();
        displayUserSelection(getUsers());
        break;

    default:
        // Illegal action
        crack_attempt();

    }

} // end main()
   /***************************************************************************/

main($action);
?>
