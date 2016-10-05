<?php

include_once ("Common.php");
include_once ("User.php");
include_once ("BookItem.php");
include_once ("MusicItem.php");

setSessionHandlers();
if (!headers_sent()) {
    header("Content-Type: text/vnd.wap.wml" );
}

// Check if the user is authenticated
checkSessionAuthenticated();
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card id="main">
    <do type="options" label="HOME">
      <go href="AppMain.php?<?php echo getSessionIdString() ?>"/>
    </do>
    <p>
      <b> Shopping Cart Items </b>
      <?php
      //$shoppingCart = $user->getShoppingCart();
      $shoppingCartItems = $shoppingCart->getItems();
      $first = true;
      $insertedSelect = false;
      for($i=0; $i < sizeof($shoppingCartItems) ; $i++) {
          if ($first) {
              printf("<select>");
	      $insertedSelect = true;
	  }
          $item = $shoppingCartItems[$i]->getItem();
          generateOptionElement("#". $item->getItemNo(),
          $item->getTitle());
          $first=false;
      }
      if ($insertedSelect) {
          printf("</select>");
      }
      ?>
    </p>
  </card>

  <?php
  // Display the details of each card
  for($i=0; $i<sizeof($shoppingCartItems); $i++) {
    $item = $shoppingCartItems[$i]->getItem();
    $itemNo = $item->getItemNo();
  ?>
 
  <card id="<?php echo $itemNo?>" >
    <do type="accept" label="CHG">
      <go href="GenChangeQuantityForm.php?selectedItem=<?php echo $itemNo?>&amp;<?php echo getSessionIdString() ?>"/>
    </do>
    <do type="options" label="BACK">
      <go href="#main" />
    </do>
    <p>
    <?php
    $item = $shoppingCartItems[$i]->getItem();
    printf("%s<br/>\n", $item->getTitle());
    if (strncasecmp($item->getItemType(), 'BOOK', 4) == 0) {
        printf("%s<br/>\n", $item->getAuthor());
    } else {
        printf("%s<br/>\n", $item->getArtist());
    }
    printf("%s<br/>\n", $item->getItemType());
    printf("Quantity: %s<br/>\n", $shoppingCartItems[$i]->getQuantity());
    printf("$$%2.2f\n",
    $item->getPrice()*$shoppingCartItems[$i]->getQuantity());
    ?>
    </p>
  </card>
  
  <?php
  } //end for
  ?>

</wml>
