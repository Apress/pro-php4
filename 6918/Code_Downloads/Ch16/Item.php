<?php

class Item 
{
    var $itemNo;
    var $itemType;
    var $price;

    function Item($itemNo, $itemType, $price)
    {
        $this->itemNo = $itemNo;
	$this->itemType = $itemType;
	$this->price = $price;
    }

    function getItemNo() 
    {
        return $this->itemNo;
    }

    function getItemType() 
    {
        return $this->itemType;
    }

    function getPrice()
    {
        return $this->price;
    }
}
?>
