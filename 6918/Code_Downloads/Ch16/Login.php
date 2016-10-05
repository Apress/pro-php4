<?php

include_once ("Common.php");
include_once ("UserFactory.php");
include_once ("ShoppingCart.php");
setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );

// Check if the form variables are null
$userId = trim($userId);	
$password = trim($password);	
if (($userId == "") || ($password == "")) { 
    sendErrorPage("The username and password you have entered is Invalid. Please try again");
    exit;
}

$funcResult = loadUser($userId);
if ($funcResult->returnValue == null) {
    sendErrorPage($functionResult->errorMessage);
    exit;
}

$user = $funcResult->returnValue;
if (!$user->checkPassword($password)) {
    sendErrorPage("Invalid Password");
    exit;
}
	
// Create user session
if (!session_start()) {
    sendErrorPage("Internal Error: Could not create user session");
    exit;
}

// Register isAuthenticated flag
if (!session_register("isAuthenticated")) {
    sendErrorPage("Internal Error: Could not add isAuthenticated variable to the session");
    exit;
}

$isAuthenticated = true;

if (!session_register("user")) {
    sendErrorPage("Internal Error: Could not add user variable to the session");
    exit;
}

$shoppingCart = new Shopping_Cart();
if (!session_register("shoppingCart")) {
    sendErrorPage("Internal Error: Could not add shoppingCart variable to the session");
    exit;
}

$userOrders = null;
if (!session_register("userOrders")) {
    sendErrorPage("Internal Error: Could not add userOrders variable to the session");
    exit;
}

// Register a two dimensional array for storing book shop contents 
if (!session_register("bookShopContent")) {
    sendErrorPage("Internal Error: Could not add bookShopContent variable to the session");
    exit;
}

// Register a two dimensional array for storing music shop contents 
if (!session_register("musicShopContent")) {
    sendErrorPage("Internal Error: Could not add musicShopContent variable to the session");
    exit;
}
	
// Register a two dimensional array for storing the search contents 
if (!session_register("searchContent")) {
    sendErrorPage("Internal Error: Could not add searchShopContent variable to the session");
    exit;
}

include_once ("AppMain.php");
?>
