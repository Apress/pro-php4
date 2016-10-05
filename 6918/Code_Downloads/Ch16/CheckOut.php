<?php

include_once ("Common.php");
include_once ("User.php");
	
setSessionHandlers();
if (!headers_sent()) {
    header("Content-Type: text/vnd.wap.wml" );
}

// Check if the user is authenticated
	checkSessionAuthenticated();
	$checkOutDone = $user->checkOut($shoppingCart);
	$shoppingCart->clear();
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card id="main'>
    <do type="options" label="HOME">
      <go href="AppMain.php?<?php echo getSessionIdString() ?>"/>
    </do>
    <p>
    <?php 
    if ($checkOutDone == FALSE) {
        printf("No Items in the Cart\n");
    } else {
        printf("Cart Items are sent for delivery<br/>");
	printf("<a href=\"#address\"> Address Details </a><br/>");
	printf("<a href=\"#cardDetails\"> Credit Card Details </a><br/>");
    }
    ?>
    </p>
  </card>

  <?php
  if ($checkOutDone == TRUE) {
      ?>
      <card id="address">
        <do type="accept" label="BACK">
          <go href="#main"/>
	</do>	
	
        <p>
	  <b> Shipping Address </b>
	  <?php
	  $shippingAddress = $user->getShippingAddress();
	  printf("%s %s<br/>\n", $user->getFirstName(), $user->getLastName());
	  printf("%s <br/>\n", $shippingAddress->getStreetAddress());
	  printf("%s <br/>\n", $shippingAddress->getCity());
	  printf("%s, %s<br/>\n", $shippingAddress->getCountry(), $shippingAddress->getZipCode());
	  ?>
	</p>
      </card>
  <?php
  }	
  ?>
  <?php
  if ($checkOutDone == TRUE) {
  ?>
 
  <card id="cardDetails">
    <do type="accept" label="BACK">
      <go href="#main"/>
    </do>	
    <p>
      <b> Card Details </b> <br/>
      <?php
      $creditCard = $user->getCreditCard();
      printf("Card No: %s <br/>\n", $creditCard->getCardNumber());
      printf("Card Type: %s <br/>\n", $creditCard->getCardType());
      printf("Expiry Date:  %s<br/>\n", convertDateFromMysqlFormat($creditCard->getExpiryDate()));
      ?>
    </p>
  </card>
  <?php
  }
  ?>
</wml>
