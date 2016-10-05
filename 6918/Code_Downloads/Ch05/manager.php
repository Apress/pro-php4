<?php

require_once("employee.php");

// Manager.php
class Manager extends Employee 
{
    var $salary;

    function Manager($firstName, $lastName, $salary) 
    {
        Employee::Employee($firstName, $lastName);

        $this->setSalary($salary);
    }

    function setSalary($salary) 
    {
        if ($salary < 0) $salary = 0;

        $this->salary = $salary;
    }

    function getWeeklyEarnings() 
    {
        return $this->salary;
    }
}

?>
