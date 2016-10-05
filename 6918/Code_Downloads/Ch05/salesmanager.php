<?php

require_once("manager.php");

define("DEFAULT_COMMISSION", .15);

// SalesManager.php
class SalesManager extends Manager 
{
    var $salary;
    var $commission; // values range from 0 to 1
    var $amountSold; // double

    function SalesManager($firstName, $lastName, $salary, 
                          $commission, $amountSold)
    {
        Manager::Manager($firstName, $lastName, $salary);
        $this->setCommission($commission);
        $this->setAmountSold($amountSold);
    }

    function setCommission($commission) 
    {
        if ($commission < 0 || $commission > 1) 
            $commission = DEFAULT_COMMISSION;

        $this->commission = $commission;
    }

    function setAmountSold($amountSold) 
    {
        if ($amountSold < 0) $amountSold = 0;

        $this->amountSold = $amountSold;
    }

    function getWeeklyEarnings() 
    {
        return Manager::getWeeklyEarnings() + 
                        ($this->commission * $this>amountSold);
    }
}

?>
