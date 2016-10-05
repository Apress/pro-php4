<?php

include_once ("Common.php");
include_once ("ShoppingCart.php");
include_once ("BookItem.php");

setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );

// Check if the user is authenticated
checkSessionAuthenticated();

$shoppingCartItem = $shoppingCart->getShoppingCartItem($selectedItem);
$shoppingCart->changeQuantity($shoppingCartItem->getItem(), $quantity);
$shoppingCartItem = $shoppingCart->getShoppingCartItem($selectedItem);

include_once ("DisplayCart.php");

?>
