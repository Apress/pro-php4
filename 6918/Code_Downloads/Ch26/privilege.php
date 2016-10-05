<?php

require("DB.php");
require("priv.classes.inc");

$sql =new DB("localhost", "", "", "userprivilege");
$sql->open();

   /***************************************************************************

   privilege.php

   Christopher Scollo   scollo@taurix.com

   ***************************************************************************

   Function Synopses:

   void displaySelection (arr aPrivileges) 
      // Generate page for selecting a privilege

   void displayDetails (obj oPriv)
      // Generate page for selected privilege

   array getPrivileges()
      // Returns an array of all privileges

   bool validate ()
      // Validate form data and set the global priv object

   bool save ()
      // Save the selected privilege

   bool delete (iPrivilegeID)
      // Remove the selected privilege

   void main (str sAction)
      // Flow control

   ***************************************************************************

   Function Declarations:

   ***************************************************************************/

function displaySelection($aPrivileges) 
{
    // Display the list of choices

    echo("<?xml version=\"1.0\"?>\n");
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
                                  "DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
      <head>
        <title>Privilege</title>
      </head>
      <body>
        <form method="post" action="privilege.php">
          <select name="priv_id">
            <?php
            foreach ($aPrivileges as $iPrivID => $sDescription) {
                echo(
                    "<option value=\"$iPrivID\">" .
                    stripslashes(htmlspecialchars($sDescription)) .
                    "</option>\n"
                );
            }
            ?>
          </select>
          <br />
          <br />
          <input type="submit" name="action" value="Add New" />
          <input type="submit" name="action" value="Change Privilege" />
          <input type="submit" name="action" value="Exit" />
        </form>
      </body>
    </html>

<?php

} // end displaySelection()

   /***************************************************************************/

function displayDetails($oPriv) 
{
    // Generate page for selected privilege

    echo("<?xml version=\"1.0\"?>\n");
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
                                  "DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
      <head>
        <title>Privilege</title>
      </head>
      <body>
        <form method="post" action="privilege.php">
          <input type="hidden" name="priv_id" 
                 value="<?php echo($oPriv->getPriv_id()); ?>" />
          <h3>Description:</h3>
          <input
            type="text"
            name="description"
            value="<?php echo(stripslashes(htmlspecialchars(
                              $oPriv->getDescription()))); ?>"
            size="50"
            maxlength="50"
          />
          <br />
          <br />
          <input type="submit" name="action" value="Save" />
          <input type="submit" name="action" value="Delete" />
          <input type="submit" name="action" value="Cancel" />
        </form>
      </body>
    </html>

<?php

} // end displayDetails()

/************************************************************************/

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
    // Validate form data and set the global priv object

    global $priv_id, $description, $oPriv;

    if ($priv_id) {
        // Make sure it's a valid PrivID
        $oPriv = new Privilege((int)$priv_id);
        if (!$oPriv->privilegeExists) return 0;
    } else {
        $priv_id = 0;
        $oPriv = new Privilege();
    }
    return 1;

} // end validate()

   /***************************************************************************/

function save()
{
    // Save the selected privilege

    global $oPriv, $description;

    $oPriv->setDescription(addslashes($description));
    if ($oPriv->privilegeExists) {
        if (!$oPriv->update()) return 0;
    } else {
        if (!$oPriv->create()) return 0;
    }
    return 1;

} // end save()

   /***************************************************************************/

function delete($oPriv) 
{
    // Remove the selected privilege

    if ($oPriv->privilegeExists) {
        return $oPriv->delete();
    }
    return 0;

} // end delete()

   /***************************************************************************/

function main($sAction) 
{
    // Flow control

    if (!validate()) return;

    switch($sAction) {
    case "":
        // Fall through

    case "Cancel":
        displaySelection(getPrivileges());
        break;

    case "Exit":
        header("Location: index.php");
        exit;

    case "Add New":
        $oPriv = new Privilege();
        displayDetails($oPriv);
        break;

    case "Change Privilege":
        displayDetails($GLOBALS["oPriv"]);
        break;

    case "Save":
        save();
        displaySelection(getPrivileges());
        break;

    case "Delete":
        delete($GLOBALS["oPriv"]);
        displaySelection(getPrivileges());
        break;

    default:
        // Illegal action
        crack_attempt();
    }

} // end main()

   /***************************************************************************/

main($action);
?>
