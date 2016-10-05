<?php

include_once("Common.php");	
include_once("CreditCard.php");	
include_once("ShippingAddress.php");	
include_once("User.php");	

function createUser($fname, $lname, $password, $userId, 
    $address, $city, $country,
    $zipCode, $gender, $age,
    $emailId, $phoneNo, $cardType,
    $cardNumber, $cardExpiryDate ) {

    // Get DB Connection
    $functionResult = getDBConnection();
    if ($functionResult->returnValue == null) {
        return $functionResult;
    }
    $link = $functionResult->returnValue;

    // Check if the user exists
    $checkUserQuery = "select count(*) from UserProfile where userId ="
                        						. "'" . $userId . "'";
    if (!($result = mysql_query($checkUserQuery, $link))) {
        return new Function_Result("Internal Error: Could not execute SQL Statement", null);
    }

    if (!($row = mysql_fetch_row($result))) {
        return new Function_Result("Internal Error: Could not fetch row from result", null);
    }

    if ($row[0] > 0) {
        return new Function_Result("User " . $userId . " exists", null);
    }
    mysql_free_result($result);

    // Create the user
    $insertUserStmt = "insert into UserProfile(fname, lname, userId, 
    password, address, city, country, zipCode, 
    gender, age, emailId, phoneNumber, cardNo, 
    expiryDate, cardType, accountBalance) values ("
    . "'" . $fname . "',"
    . "'" . $lname . "',"
    . "'" . $userId . "',"
    . "'" . crypt($password, CRYPT_STD_DES) . "',"
    . "'" . $address . "',"
    . "'" . $city . "',"
    . "'" . $country . "',"
    . $zipCode. ","
    . "'" . $gender . "',"
    . $age . ","
    . "'" . $emailId . "',"
    . "'" . $phoneNo . "',"
    . "'" . $cardNumber . "',"
    . "'" . convertDateToMysqlFormat($cardExpiryDate) . "',"
    . "'" . $cardType . "',"
    . "0 )" ;

    if (!($result = mysql_query($insertUserStmt, $link))) {
        return new Function_Result("Internal Error: Could not execute sql query", null);
    }
    return new Function_Result(null, $userId);
}

function loadUser($userId) 
{
    // Get DB Connection
    $functionResult = getDBConnection();
    if ($functionResult->returnValue == null) {
        return $functionResult;
    }
    $link = $functionResult->returnValue;
    $selectUserStmt = "select fname, lname, userId,
    password, address, city, country, zipCode,  
    gender, age, emailId, phoneNumber, cardNo, 
    expiryDate, cardType, accountBalance 
    from UserProfile where userId="."'".$userId."'";

    // Execute select statement
    if (!($result = mysql_query($selectUserStmt, $link))) {
        echo $selectUserStmt;
	return new Function_Result(" Internal Error: Could not execute SQL Query", null);
    }

    if (!($row = mysql_fetch_row($result))) {
        return new Function_Result("User " . $userId . " does not exist", null);
    }

    $firstName = $row[0];
    $lastName = $row[1];
    $userId = $row[2];
    $password = $row[3];
    $shippingAddress = 
    new ShippingAddress($row[4], $row[5], $row[6], $row[7]);
    $gender = $row[8];
    $age = $row[9];
    $emailId = $row[10];
    $phoneNumber = $row[11];
    $creditCard = new CreditCard($row[12], $row[14], $row[13]);
    $accountBalance = $row[15];

    $user = new User($firstName, $lastName, $userId, $password, 
    $gender, $age, $emailId, $phoneNumber, 
    $accountBalance, $shippingAddress, 
    $creditCard);
    $mysql_free_result($result);
    return new Function_Result(null, $user);
}

?>
