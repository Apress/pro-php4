<?php

include_once ("Common.php");
include_once ("MusicShop.php");
setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );
checkSessionAuthenticated();

if (!$musicShopContent) {
    $currentIndex=0;
    $musicShop = new Music_Shop();
    $funcResult = $musicShop->getItems();
    if ($funcResult->returnValue == null) {
        sendErrorPage($funcResult->errorMessage);
        exit;
    }
    $musicShopContent = $funcResult->returnValue;

}
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card id="main">
    <do type="options" label="HOME">
      <go href="AppMain.php?<?php echo getSessionIdString() ?>"/>
    </do>
    <p>
      <b> Music Shop Items</b>
      <select>
        <?php
	$i = 0;
	$contentSize = sizeof($musicShopContent);
	while (($i<3) && ($currentIndex < $contentSize)) {
	    generateOptionElement("#". $musicShopContent[$currentIndex]->getItemNo(),
	    $musicShopContent[$currentIndex]->getTitle());
	    $generateDescCard[] = $currentIndex;
	    $currentIndex++;
	    $i++;
	}
	if ($currentIndex < $contentSize) {
	    $nextHref = "ViewMusicShop.php?currentIndex=" . 
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
      $itemNo = $musicShopContent[$generateDescCard[$i]]->getItemNo();
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
    printf("%s<br/>\n", $musicShopContent[$generateDescCard[$i]]->getTitle());
    printf("%s<br/>\n", $musicShopContent[$generateDescCard[$i]]->getArtist());
    printf("%s<br/>\n", $musicShopContent[$generateDescCard[$i]]->getItemType());
    printf("$$%2.2f\n",$musicShopContent[$generateDescCard[$i]]->getPrice());
    ?>
  </p>
  </card>
    <?php
    } //end for
    ?>
</wml>
