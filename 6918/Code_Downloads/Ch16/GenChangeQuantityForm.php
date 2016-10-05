<?php

include_once ("Common.php");
include_once("MusicItem.php");
include_once("BookItem.php");
include_once("ShoppingCart.php");


setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );

// Check if the user is authenticated
checkSessionAuthenticated();
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card>
    <do type="options" label="HOME">
      <go href="AppMain.php?<?php echo getSessionIdString() ?>"/>
    </do>
    <p>
      Enter Quantity for  <?php echo $shoppingCart[$selectedItem][0][1] ?>
      <input name="quantity" type="text" format="N" 
      value="<?php echo $shoppingCart[$selectedItem][1]?>" />
      <do type="accept" label="Submit">
        <go href="ChangeQuantity.php?selectedItem=<?php echo $selectedItem ?>&amp;quantity=$quantity&amp;<?php echo getSessionIdString() ?>" />
      </do>
     </p>
  </card>
</wml>
