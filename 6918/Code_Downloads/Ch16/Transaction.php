<?php

    class Transaction 
    {
        var $userId;
	var $item;
	var $quantity;
	var $date;
	var $status;
	var $orderNo;

        function Transaction($userId, $item, $quantity, $date, $status, $orderNo) 
        {
	    $this->userId = $userId;
	    $this->item = $item;
	    $this->quantity = $quantity;
	    $this->date = $date;
	    $this->status = $status;
	    $this->orderNo = $orderNo;
	}

	function getUserId() 
        {
	    return $this->userId;
	}

	function getItem() 
        {
	    return $this->item;
	}

	function getQuantity() 
        {
	    return $this->quantity;
	}

	function getDate() 
        {
	    return $this->date;
	}

	function getStatus() 
        {
	    return $this->status;
	}

	function getOrderNo() 
        {
	    return $this->orderNo;
	}
    }
?>
