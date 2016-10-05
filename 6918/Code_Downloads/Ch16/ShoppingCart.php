<?php
	
include ("MusicItem.php");
include ("BookItem.php");

class Shopping_Cart 
{
    var $shoppingCartItems;

    function Shopping_Cart() 
    {
       $this->shoppingCartItems = array();
    }

    function addItem($item, $quantity){
        $itemNo = $item->getItemNo();
	$shoppingCartItem = $this->getShoppingCartItem($itemNo);
	if (!$shoppingCartItem) {
	    $this->shoppingCartItems[] = 
	    new ShoppingCartItem($item, $quantity);
	} else {
	    $shoppingCartItem->addQuantity($quantity);
	}
    }

    function &getShoppingCartItem($itemNo) 
    {
        for($i=0; $i<sizeof($this->shoppingCartItems); $i++) {
	    $shoppingCartItem = & $this->shoppingCartItems[$i];
	    $item = $shoppingCartItem->getItem();
	    if ($item->getItemNo() == $itemNo) {
	    return $this->shoppingCartItems[$i];
	}
		}
	return null;
    }

    function removeItem($item)
    {
        $shoppingCartItem = $this->getShoppingCartItem($item->getItemNo());
	if ($shoppingCartItem != null) {
	    $shoppingCartItem->setQuantity(0);
	}
    }

    function changeQuantity($item, $newQuantity)
    {
        $shoppingCartItem = &$this->getShoppingCartItem($item->getItemNo());
	if ($shoppingCartItem != null) {
	    $shoppingCartItem->setQuantity($newQuantity);
	}
		$shoppingCartItem = &$this->getShoppingCartItem($item->getItemNo());
    }

    function getItems() 
    {
        $retItems = array();
	for($i=0; $i<sizeof($this->shoppingCartItems); $i++) {
	    $shoppingCartItem = $this->shoppingCartItems[$i];
	    if ($shoppingCartItem->getQuantity() != 0) {
	        $retItems[] = $shoppingCartItem;
	    }
	}
	return $retItems;
    }

    function clear()
    {
        $this->shoppingCartItems = array();
    }
}

class ShoppingCartItem 
{
    var $item;
    var $quantity;

    function ShoppingCartItem($item, $quantity) 
    {
        $this->item = $item;
	$this->quantity = $quantity;
    }

    function setQuantity($quantity) 
    {
        $this->quantity = $quantity;
    }

    function addQuantity($quantity) 
    {
        $this->quantity += $quantity;
    }

    function getItem() 
    {
         return $this->item;
    }

    function getQuantity() 
    {
        return $this->quantity;
    }

}
?>
