<?php

include_once ("Common.php");
include_once ("BookShop.php");
setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );

checkSessionAuthenticated();

if (!$bookShopContent) {
    $currentIndex=0;
    $bookShop = new BookShop();
    $funcResult = $bookShop->getItems();
    if ($funcResult->returnValue == null) {
        sendErrorPage($funcResult->errorMessage);
	exit;
    }
    $bookShopContent = $funcResult->returnValue;
}
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card id="main">
    <do type="options" label="HOME">
      <go href="AppMain.php4?<?php echo getSessionIdString() ?>"/>
    </do>

    <p>
      <b> Book Shop Items</b>
	<select>
	  <?php
	  $i = 0;
	  $contentSize = sizeof($bookShopContent);
	  while (($i<3) && ($currentIndex < $contentSize)) {
	      generateOptionElement("#". $bookShopContent[$currentIndex]->getItemNo(),
	      $bookShopContent[$currentIndex]->getTitle());
	      $generateDescCard[] = $currentIndex;
	      $currentIndex++;
	      $i++;
	  }
	  if ($currentIndex < $contentSize) {
	      $nextHref = "ViewBookShop.php4?currentIndex=" . 
	      $currentIndex . "&amp;" . getSessionIdString();
	      generateOptionElement($nextHref, "View Next Items");
	  }
	  ?>
	</select>
    </p>
  </card>

  <?php

  // Display the details of each card
  for($i=0; $i<sizeof($generateDescCard); $i++) {
      $itemNo = $bookShopContent[$generateDescCard[$i]]->getItemNo();
  ?>
  
  <card id="<?php echo $itemNo?>" >
    <do type="accept" label="ADD">
      <go href="addToCart.php4?selectedItem=<?php echo $itemNo . 
          "&amp;" . getSessionIdString() ?>"/>
    </do>
    <do type="options" label="BACK">
      <go href="#main" />
    </do>
  <p>
  
    <?php
    printf("%s<br/>\n", $bookShopContent[$generateDescCard[$i]]->getTitle());
    printf("%s<br/>\n", $bookShopContent[$generateDescCard[$i]]->getAuthor());
    printf("Book<br/>\n");
    printf("$$%2.2f\n",$bookShopContent[$generateDescCard[$i]]->getPrice());
    ?>
  </p>
  </card>
  <?php
  } //end for
  ?>
</wml>
