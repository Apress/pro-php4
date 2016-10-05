<?php

include_once("Common.php");
include_once("Transaction.php");
include_once("BookItem.php");
include_once("MusicItem.php");

class UserStorage 
{
    var $userId;

    function UserStorage($userId) 
    {
        $this->userId = $userId;
    }

    function getTransactions() 
    {
        $transactions = array();
      	// Get DB Connection
      	$functionResult = getDBConnection();
      	if ($functionResult->returnValue == null) {
            return $functionResult;
      	}

      	$link = $functionResult->returnValue;
      	$selectStmt = "select Transaction.itemNo, title, author, quantity, date, status, orderNo, itemType from BookShop ,  Transaction   where BookShop.itemNo = Transaction.itemNo and userId = " . "'" . $this->userId . "'";

      	// Execute the query
      	if (!($result = mysql_query($selectStmt, $link))) {
            return FunctionResult("Internal Error: Could not execute sql query", null);
        }
      	while (($row = mysql_fetch_array($result, MYSQL_NUM))) {
	    $item = new BookItem($row[0], $row[7], null, $row[1], $row[2]);
            $transaction = new Transaction($this->userId, $item, $row[3], $row[4], $row[5], $row[6]);
	    $transactions[] = $transaction;
      	}
      	
        mysql_free_result($result);

      	$selectStmt = "select Transaction.itemNo, title, artist, quantity, date, status, orderNo, itemType from MusicShop ,  Transaction   where MusicShop.itemNo = Transaction.itemNo and userId = " . "'" . $this->userId . "'";

      	// Execute the query
      	if (!($result = mysql_query($selectStmt, $link))) {
            return Function_Result("Internal Error: Could not execute sql query", null);
        }
      	while (($row = mysql_fetch_array($result, MYSQL_NUM))) {
	    $item = new MusicI_tem($row[0], $row[7], null, $row[1], $row[2]);
            $transaction = new Transaction($this->userId, $item, $row[3], $row[4], $row[5], $row[6]);
	    $transactions[] = $transaction;
      	}
  
      	mysql_free_result($result);
	    return $transactions;

    }

    function saveTransactions($accountBalance, $transactions) 
    {
        // Get DB Connection
      	$functionResult = getDBConnection();
      	if ($functionResult->returnValue == null) {
            return $functionResult;
      	}
      	$link = $functionResult->returnValue;

	// Generate the sql insert statment for inserting the user transaction
	$insertStmt = "insert into Transaction(userId, itemNo, quantity, date, status) values ";
        for($i=0; $i < sizeof($transactions) ; $i++) {
	    $transaction = $transactions[$i];
	    $item = $transaction->getItem();
     	    $insertStmt = $insertStmt . "('" . $transaction->getUserId() . "','" .
            $item->getItemNo() . "'," . $transaction->getQuantity() . ",'" . 
            $transaction->getDate() . "','" . $transaction->getStatus() . "')";
            if ($i < (sizeof($transactions)-1)) {
	        $insertStmt = $insertStmt . ",";
	    }
	}
      	if (!($result = mysql_query($insertStmt, $link))) {
            return new FunctionResult("Internal Error: Could not execute SQL Query", null);
      	}

        // Update account balance
            $updateBalanceStmt = "update UserProfile set accountBalance =  " . $accountBalance . " where userId = " . "'" . $this->userId . "'";

      	if (!($result = mysql_query($updateBalanceStmt, $link))) {
            return new Function_Result("Internal Error: Could not execute SQL Query", null);
        }
		
	return new Function_Result(null, null);
    }

}	
?>
