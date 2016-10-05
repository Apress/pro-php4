<?php

include_once ("ShoppingCart.php");
include_once ("UserStorage.php");
include_once ("ShippingAddress.php");
include_once ("CreditCard.php");
include_once ("Common.php");
include_once ("Transaction.php");
include_once ("BookItem.php");
include_once ("MusicItem.php");

class User 
{
    var $firstName;	
    var $lastName;	
    var $password;	
    var $gender;	
    var $age;	
    var $emailId;	
    var $phoneNumber;	
    var $accountBalance;	
    var $shippingAddress;	
    var $creditCard;	
    var $userStorage;
	
    function User( $firstName, $lastName, $userId, $password, $gender,
				$age, $emailId, $phoneNumber, $accountBalance,
				$shippingAddress, $creditCard) 
    {
        $this->firstName = $firstName;
	$this->lastName = $lastName;
	$this->userId = $userId;
	$this->password = $password;
	$this->gender = $gender;
	$this->age = $age;
	$this->emailId = $emailId;
	$this->phoneNumber = $phoneNumber;
	$this->accountBalance = $accountBalance;
	$this->shippingAddress = $shippingAddress;
	$this->creditCard= $creditCard;
	$this->userStorage = new UserStorage($userId);
    }

    function getFirstName() 
    {
        return $this->firstName;
    }

    function getLastName()
    {
        return $this->lastName;
    }

    function getUserId()
    {
        return $this->userId;
    }

    function getGender() 
    {
        return $this->gender;
    }

    function getAge()
    {
        return $this->age;
    }

    function getEmailId()
    {
        return $this->emailId;
    }

    function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    function getCreditCard()
    {
        return $this->creditCard;
    }

    function getAccountBalance()
    {
        return $this->accountBalance;
    }

    function checkPassword($password) 
    {
        $cryptPassword = crypt($password, CRYPT_STD_DES);
	if ($this->password == $cryptPassword) {
	    return TRUE;
        } else {
            return FALSE;
        }
    }


    function checkOut($shoppingCart) 
    {
        $transactions = array();
	$shoppingCartItems = $shoppingCart->getItems();
	for ($i=0; $i < sizeof($shoppingCartItems); $i++) {
            $shoppingCartItem = $shoppingCartItems[$i];
	    $item = $shoppingCartItem->getItem();
	    $transactions[] = new Transaction($this->userId, $item, $shoppingCartItem->getQuantity(), getDateString(), "Pending", null);
	    $this->accountBalance += $shoppingCartItem->getQuantity()* $item->getprice();
	}

	$storage = $this->userStorage;
	if (sizeof($transactions) > 0) {
            $storage->saveTransactions($this->accountBalance, $transactions);
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

    function getTransactions() 
    {
        $storage = $this->userStorage;
	return $storage->getTransactions();
    }

}
?>
