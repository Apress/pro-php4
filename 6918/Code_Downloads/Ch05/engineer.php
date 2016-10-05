<?php
// engineer.php

class Engineer 
{
    var $firstName, $lastName;

    function Engineer($firstName, $lastName, $engineerType) 
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    function designProject($project) 
    {
        // .. code to assign this engineer to a project
    }

    function getEngineerType() 
    {
        return $this->engineerType;
    }
}

?>
