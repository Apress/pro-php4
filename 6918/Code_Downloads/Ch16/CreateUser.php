<?php

include ("Common.php");
include ("UserFactory.php");
setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );

// Check if the form variables are null
$fname = trim($fname);	
$lname = trim($lname);	
$userId = trim($userId);	
$password = trim($password);	
$address = trim($address);	
$city = trim($city);	
$country = trim($country);	
$zipCode = trim($zipCode);	
$gender = trim($gender);	
$age = trim($age);	
$emailId = trim($emailId);	
$phoneNo = trim($phoneNo);	
$cardType = trim($cardType);	
$cardNumber = trim($cardNumber);	
$cardExpiryDate = trim($cardExpiryDate);	

if (($fname == "") || ($lname== "") || ($password == "") || 
  ($userId == "") || ($address== "") || ($city == "") || 
  ($country == "") || ($zipCode == "") || ($gender == "") ||
  ($age == "") || ($emailId == "") || ($phoneNo == "") || 
  ($cardType== "") || ($cardNumber == "") || 
  ($cardExpiryDate == "")) {
    sendErrorPage("Error: Not all the form fields are filled");
    exit;
}

createUser($fname, $lname, $password, $userId,
  $address, $city, $country,
  $zipCode, $gender, $age,
  $emailId, $phoneNo, $cardType,
  $cardNumber, $cardExpiryDate );

?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN"
	"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card>
    <p>
      User <?php echo $userId ?> created. Go to the <a href="Main.php4#login"> Login page </a>
    </p>
  </card>
</wml>
