<?php

include_once ("Common.php");
include_once ("BookShop.php");
include_once ("MusicShop.php");
include_once ("User.php");

setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );

// Check if the user is authenticated
checkSessionAuthenticated();
$bookShop = new Book_Shop();
$musicShop = new Music_Shop();
$funcResult = $bookShop->getItem($selectedItem);
if ($funcResult->returnValue == null) {
    if ($funcResult->errorMessage == null) {
        // search in music shop
	$funcResult = $musicShop->getItem($selectedItem);
	if ($funcResult->returnValue == null) {
	    sendErrorPage($funcResult->errorMessage);
	    exit;
	} else {
	    $item = $funcResult->returnValue;
	}
    } else {
	sendErrorPage($funcResult->errorMessage);
	exit;
    }
} else {
    $item = $funcResult->returnValue;
}

// Add the selected item to the shopping cart
$shoppingCart->addItem($item, 1);

?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card>
    <do type="options" label="HOME">
      <go href="AppMain.php4?<?php echo getSessionIdString() ?>"/>
    </do>
    <p>
      The item <a href="#details"> <?php echo $item->getTitle()?></a> has been added to your cart. <br /> 
      <a href="DisplayCart.php?<?php echo getSessionIdString() ?>">Display Cart </a>
    </p>
  </card>

  <card id="details">
    <do type="options" label="HOME">
      <go href="AppMain.php4?<?php echo getSessionIdString()?>"/>
    </do>
    <p>
      <?php
      printf("%s <br/>\n",$item->getTitle());
      if (strncasecmp($item->getItemType(), 'BOOK', 4) == 0) {
          printf("%s<br/>\n", $item->getAuthor());
      } else {
          printf("%s<br/>\n", $item->getArtist());
      }
      printf("%s <br/>\n",$item->getItemType());
      printf("$$%s <br/>\n",$item->getPrice());
      ?>
    </p>
  </card>
</wml>
