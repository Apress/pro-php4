<?php

// Employee.php
class Employee 
{
    var $firstName;
    var $lastName;

    function Employee($firstName, $lastName) 
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    function getFirstName() {
        return $this->firstName;
    }

    function getLastName() {
        return $this->lastName;
    }

    // Abstract method
    function getWeeklyEarnings() {}
}

?>
