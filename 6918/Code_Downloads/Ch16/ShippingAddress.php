<?php

class ShippingAddress 
{
    var $streetAddress;
    var $city;
    var $country;
    var $zipCode;

    function Shipping_Address($streetAddress, $city, $country, $zipCode)
    {
        $this->streetAddress = $streetAddress;
	$this->city = $city;
	$this->country = $country;
	$this->zipCode = $zipCode;
    }

    function getStreetAddress() 
    {
        return $this->streetAddress;
    }

    function getCity() 
    {
        return $this->city;
    }

    function getCountry() 
    {
        return $this->country;
    }

    function getZipCode() 
    {
        return $this->zipCode;
    }

}
?>
