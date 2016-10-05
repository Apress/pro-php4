<?php

/***************************************************************************

priv.classes.inc

   Classes:
   Privilege

***************************************************************************/

/***************************************************************************/

class Privilege 
{
   /****************

      Christopher Scollo  scollo@taurix.com

   *****************

      Property Synopses:

      int priv_id
      str description
      bool privilegeExists

   *****************

      Method Synopses:

      void Privilege ([int iPrivilegeID])
         // Constructor

      bool populatePrivilege (int iPrivilegeID)
         // Populate the data for this privilege

      bool create ()
         // Add the new privilege to the database

      bool delete()
         // Delete the privilege from the database and destroy the object

      bool update()
         // Update the privilege in the database

      str toString ()
         // Represent the object as a string

      int getPriv_id ()
         // Returns the property

      bool setPriv_id (int iVal)
         // Set the property

      str getDescription ()
         // Returns the property

      bool setDescription (str sVal)
         // Set the property

   ****************/

    // Property Declarations:

    var $priv_id;
    var $description;
    var $privilegeExists = 0;

    // Method Declarations:

    function Privilege($iPrivilegeID = 0) 
    {
        // Constructor

        if ($iPrivilegeID) $this->populatePrivilege($iPrivilegeID);
    } // end Privilege()

    /****************/

    function populatePrivilege($iPrivilegeID) 
    {
        // Populate the data for this privilege

	global $sql;

        $sql->query("SELECT * FROM Privilege WHERE 
                                priv_id=$iPrivilegeID");

        $row = $sql->fetchObject();

        $this->setDescription($row->description);
        $this->privilegeExists = 1;
        
        $this->setPriv_id($iPrivilegeID);
        return $this->privilegeExists;

    } // end populatePrivilege()

    /****************/

    function create() 
    {
        // Add the new privilege to the database

        global $sql;

        $sDescription = $this->getDescription();

        $sql->query("INSERT INTO Privilege (description) VALUES 
                                ('$sDescription')");
    } // end create()

    /****************/

    function delete() 
    {
        // Delete the privilege from the database

        global $sql;
 
        $iPrivilegeID = $this->getPriv_id();
        if (!$iPrivilegeID) return;

        // First remove from UserPrivilege
        $sql->query("DELETE FROM UserPrivilege WHERE 
                                priv_id=$iPrivilegeID");

        // Next remove from Privilege
        $sql->query("DELETE FROM Privilege WHERE 
                                priv_id=$iPrivilegeID");

        $this->privilegeExists = 0;
    } // end delete()

    /****************/

    function update()
    {
        // Update the privilege in the database

        global $sql;

        $iPrivilegeID = $this->getPriv_id();
        $sDescription = $this->getDescription();
        if (!$iPrivilegeID) return;

        $sql->query("UPDATE Privilege SET 
                               description='$sDescription' 
                               WHERE priv_id=$iPrivilegeID");
    } // end update()

    /****************/

    function getPriv_id() 
    {
        // Returns the property

        return $this->priv_id;
    } // end getPriv_id()

    /****************/

    function setPriv_id($iVal) 
    {
        // Set the property

        $this->priv_id = (int)$iVal;
        return 1;
    } // end setPriv_id()

    /****************/

    function getDescription() 
    {
        // Returns the property

        return $this->description;
    } // end getDescription()

    /****************/

    function setDescription($sVal) 
    {
        // Set the property

        if (strlen ($sVal) > 50) return 0;
        $this->description = $sVal;
        return 1;
    } // end setDescription()

    /****************/

} // end Privilege

/***************************************************************************/

class User 
{

   /****************

      Christopher Scollo  scollo@taurix.com

   *****************

      Property Synopses:

      str username
      str fullname
      arr privileges[]
      bool userExists

   *****************

      Method Synopses:

      void User ([str sUsername])
         // Constructor

      bool populateUser (str sUsername)
         // Populate the data for this user

      void populatePrivileges ()
         // Fill the privileges array for this user

      void addPrivilege (int iPrivilegeID)
         // Grant the user a new privilege

      void removePrivilege (int iPrivilegeID)
         // Revoke a privilege from this user

      bool hasPrivilege (int iPrivilegeID)
         // Determine whether user has specified privilege

      void create()
         // Create new user in the database

      void update()
         // Update the user in the database

      void delete()
         // Remove the user from the database

      str getUsername ()
         // Returns the property

      bool setUsername (str sVal)
         // Set the property

      str getFullname ()
         // Returns the property

      bool setFullname (str sVal)
         // Set the property

   ****************/

   // Property Declarations:

    var $username;
    var $fullname;
    var $privileges;
    var $userExists = 0;

   /****************/

   // Method Declarations:

   /****************/

    function User($sUsername = "") 
    {
        // Constructor

        if ($sUsername) $this->populateUser($sUsername);
    } // end User()

    /****************/

    function populateUser($sUsername) 
    {
        // Populate the data for this user

	global $sql;

        $sql->query("SELECT * FROM User WHERE 
                                username='$sUsername'");

        $row = $sql->fetchObject();

        $this->setFullname($row->fullname);
        $this->userExists = 1;

        $this->setUsername($sUsername);
        return $this->userExists;

    } // end populateUser()

    /****************/

    function populatePrivileges() 
    {
        // Fill the privileges array for this user

        global $sql;

        $sql->query("SELECT priv_id FROM UserPrivilege 
                                WHERE username='" . 
                                $this->getUsername() . "'");
        
        $this->privileges = array(); // Wipe out existing elements of array
        
        while ($row = $sql->fetchObject()) {
            $this->privileges[] = $row->priv_id;
        }
    } // end populatePrivileges()

    /****************/

    function addPrivilege($iPrivilegeID) 
    {
        // Grant the user a new privilege

	global $sql;

        // Make sure user doesn't already have this privilege:
        if ($this->hasPrivilege($iPrivilegeID)) return;

        // Make sure it's a valid PrivID:
        $oPriv = new Privilege($iPrivilegeID);
        if (!$oPriv->privilegeExists) return;

        // Add the privilege:
        $sUsername = $this->getUsername();

        $sql->query("INSERT INTO UserPrivilege VALUES 
                                ('$sUsername', $iPrivilegeID)");

        $this->privileges[] = $iPrivilegeID;
    } // end addPrivilege()

    /****************/

    function removePrivilege($iPrivilegeID) 
    {
        // Revoke a privilege from the user

        global $sql;

        // Make sure user has this privilege:
        if (!$this->hasPrivilege($iPrivilegeID)) return;

        $sUsername = $this->getUsername();
        $sql->query("DELETE from UserPrivilege WHERE 
                                privilegeid=$iPrivilegeID AND 
                                username='$sUsername'");

        $iIndex = array_search($iPrivilegeID, $this->privileges);
        unset($this->privileges[$iIndex]);
    } // end removePrivilege()

    /****************/

    function hasPrivilege($iPrivilegeID) 
    {
        // Determine whether user has specified privilege

        // Initialize privileges if necessary:
        if (!is_array($this->privileges)) $this->populatePrivileges();

        return in_array($iPrivilegeID, $this->privileges);
    } // end hasPrivilege()

    /****************/

    function create() 
    {
        // Add new user to database

        global $sql;

        $sUserName = $this->getUsername();
        $sFullName = $this->getFullname();

        $sql->query("INSERT INTO User VALUES ('$sUserName', '$sFullName')");
    } // end create()

    /****************/

    function update() 
    {
        // Update user in database

        global $sql;

        $sUserName = $this->getUsername();
        $sFullName = $this->getFullname();

        $sql->query("UPDATE User SET fullname='$sFullName' 
                                WHERE username='$sUserName'");
    } // end update()

    /****************/

    function delete() 
    {
        // Remove the user from the database

        global $sql;

        $sUserName = $this->getUsername();

        // First remove from UserPrivilege
        $sql->query("DELETE FROM UserPrivilege 
                                WHERE username='$sUserName'");

        // Next remove from Privilege
        $sql->query("DELETE FROM User 
                                WHERE username='$sUserName'");

        $this->userExists = 0; 
    } // end delete()

    /****************/

    function getUsername() 
    {
        // Returns the property

        return $this->username;
    } // end getUsername()

    /****************/

    function setUsername($sVal) 
    {
        // Set the property

        // Valid if alphanumeric or underscores between 5 and 10 chars inc
        if (!ereg("^[a-zA-Z0-9_]{5,10}$", $sVal)) return 0;
        $this->username = $sVal;
       return 1;
    } // end setUsername()

    /****************/

    function getFullname() 
    {
        // Returns the property

        return $this->fullname;
    } // end getFullname()

    /****************/

    function setFullname($sVal) 
    {
        // Set the property

        if (strlen($sVal) > 50) return 0;
        $this->fullname = $sVal;
        return 1;
    } // end setFullname()

    /****************/
} // end User

/***************************************************************************/

?>
