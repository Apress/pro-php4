<?php

class CreditCard 
{
    var $cardNumber;
    var $expiryDate;
    var $cardType;

    function Credit_Card($cardNumber, $cardType, $expiryDate)
    {
        $this->cardNumber = $cardNumber;
	$this->cardType = $cardType;
	$this->expiryDate = $expiryDate;
    }

    function getCardNumber() 
    {
        return $this->cardNumber;
    }

    function getCardType() 
    {
        return $this->cardType;
    }

    function getExpiryDate()
    {
        return $this->expiryDate;
    }

}
?>
