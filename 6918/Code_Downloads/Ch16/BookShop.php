<?php

include_once ("Common.php");
include_once ("BookItem.php");

class Book_Shop 
{
    function getItems() 
    {
        // Get DB Connection
	$functionResult = getDBConnection();
	if ($functionResult->returnValue == null) {
   	    return $functionResult;
	}
	$link = $functionResult->returnValue;

	// Select all the books from the bookshop table
	$bookShopSelectQuery = "Select itemNo, itemType, price, title, author from BookShop";

      	// Execute the query
      	if (!($result = mysql_query($bookShopSelectQuery, $link))) {
       	    return new FunctionResult("Internal Error: Could not execute sql query ", null);
      	}

	$bookShopContent = null;
      	while (($row = mysql_fetch_array($result, MYSQL_NUM))) {
       	    $bookShopContent[] = new BookItem($row[0], $row[1], $row[2], $row[3], $row[4]);
      	}
      	mysql_free_result($result);
	return new FunctionResult(null, $bookShopContent);
    }

    function getItem($itemNo) 
    {
        // Get DB Connection
        $functionResult = getDBConnection();
	if ($functionResult->returnValue == null) {
   	    return $functionResult;
	}
	$link = $functionResult->returnValue;

      	$bookShopSelectQuery = "Select itemNo, itemType, price, title, author from BookShop where itemNo='" . $itemNo . "'";

      	// Execute the query
      	if (!($result = mysql_query($bookShopSelectQuery, $link))) {
            return new Function_Result("Internal Error: Could not execute sql query ", null);
      	}
      	$row = mysql_fetch_array($result, MYSQL_NUM);
	if ($row == null) {
	    return new Function_Result(null, null);
	} else {
	    $item = new Book_Item($row[0], $row[1], $row[2], $row[3], $row[4]);
	    return new Function_Result(null, $item);
	}

    }

    function search($searchText) 
    {
        $searchStmt = "Select itemNo, itemType, price, title, author from BookShop where author like '%" . $searchText . "%' or title like '% " . $searchText . "%'" ;
	$funcResult = $this->getSearchResults($searchStmt); 
	return $funcResult->returnValue;
    }

    function searchByTitle($searchText) 
    {
        $searchStmt = "Select itemNo, itemType, price, title, author from BookShop where title like '% " . $searchText . "%'" ;
	$funcResult = $this->getSearchResults($searchStmt); 
	return $funcResult->returnValue;
    }

    function searchByAuthor($searchText) 
    {
        $searchStmt = "Select itemNo, itemType, price, title, author from BookShop where author like '% " . $searchText . "%'" ;
	$funcResult = $this->getSearchResults($searchStmt); 
	return $funcResult->returnValue;
    }

    function getSearchResults($searchStmt) 
    {
        // Get DB Connection
	$functionResult = getDBConnection();
	if ($functionResult->returnValue == null) {
   	    return $functionResult;
	}
	$link = $functionResult->returnValue;
	// Execute 
	if (!($result = mysql_query($searchStmt, $link))) {
            return new Function_Result("Internal Error: Could not execute sql query ", null);
	}
	$searchResults = null;
      	while (($row = mysql_fetch_array($result, MYSQL_NUM))) {
            $searchResults[] = new Book_Item($row[0], $row[1], $row[2], $row[3], $row[4]);
      	}
      	mysql_free_result($result);
	return new Function_Result(null, $searchResults);
    }
}	
?>
