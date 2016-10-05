<?php

include_once ("Common.php");
include_once ("MusicItem.php");

class MusicShop 
{
    function getItems() 
    {
        // Get DB Connection
	$functionResult = getDBConnection();
	if ($functionResult->returnValue == null) {
   	    return $functionResult;
	}
	$link = $functionResult->returnValue;

	$musicShopSelectQuery = "Select itemNo, itemType, price, title, artist from MusicShop";

      	// Execute the query
      	if (!($result = mysql_query($musicShopSelectQuery, $link))) {
            return new Function_Result("Internal Error: Could not execute sql query ", null);
      	}

	$musicShopContent = null;
      	while (($row = mysql_fetch_array($result, MYSQL_NUM))) {
            $musicShopContent[] = new MusicItem($row[0], $row[1], $row[2], $row[3], $row[4]);
      	}
      	mysql_free_result($result);
	return new Function_Result(null, $musicShopContent);
    }

    function getItem($itemNo) 
    {
        // Get DB Connection
	$functionResult = getDBConnection();
	if ($functionResult->returnValue == null) {
	    return $functionResult;
	}
	$link = $functionResult->returnValue;

      	$musicShopSelectQuery = "Select itemNo, itemType, price, title, artist from MusicShop where itemNo='" . $itemNo . "'";

      	// Execute the query
      	if (!($result = mysql_query($musicShopSelectQuery, $link))) {
       	    return new Function_Result("Internal Error: Could not execute sql query ", null);
      	}
      	$row = mysql_fetch_array($result, MYSQL_NUM);
	if ($row == null) {
	    return new Function_Result(null, null);
	} else {
	    $item = new Music_Item($row[0], $row[1], $row[2], $row[3], $row[4]);
	    return new Function_Result(null, $item);
	}

    }

    function search($searchText) 
    {
        $searchStmt = "Select itemNo, itemType, price, title, artist from MusicShop where artist like '%" . $searchText . "%' or title like '% " . $searchText . "%'" ;
	$funcResult = $this->getSearchResults($searchStmt); 
	return $funcResult->returnValue;
    }

    function searchByTitle($searchText) 
    {
        $searchStmt = "Select itemNo, itemType, price, title, artist from MusicShop where title like '% " . $searchText . "%'" ;
	$funcResult = $this->getSearchResults($searchStmt); 
	return $funcResult->returnValue;
    }

    function searchByArtist($searchText) 
    {
        $searchStmt = "Select itemNo, itemType, price, title, artist from MusicShop where artist like '% " . $searchText . "%'" ;
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
	    return new FunctionResult("Internal Error: Could not execute sql query ", null);
	}
	$searchResults = null;
      	while (($row = mysql_fetch_array($result, MYSQL_NUM))) {
            $searchResults[] = new MusicItem($row[0], $row[1], $row[2], $row[3], $row[4]);
      	}
      	mysql_free_result($result);
	    return new FunctionResult(null, $searchResults);
	}
    }	
?>
