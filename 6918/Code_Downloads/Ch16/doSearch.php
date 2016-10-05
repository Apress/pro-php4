<?php

include_once ("Common.php");
include_once ("BookShop.php");
include_once ("MusicShop.php");

setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );
checkSessionAuthenticated();

// Execute sql query and read the contents of the book shop
if (!$searchContent) {
    $currentIndex=0;
    $bookShop = new BookShop();
    $musicShop = new MusicShop();
    if ($searchType == "Book by Title") {
        $searchContent = $bookShop->searchByTitle($searchText);
    } else if ($searchType == "Book by Author") {
        $searchContent = $bookShop->searchByAuthor($searchText);
    } else if ($searchType == "Music Album by Title") {
        $searchContent = $musicShop->searchByTitle($searchText);
    } else if ($searchType == "Music Album by Artist") {
        $searchContent = $musicShop->searchByArtist($searchText);
    } else {
        $searchContent1 = $musicShop->search($searchText);
	$searchContent2 = $bookShop->search($searchText);
	$searchContent = array_merge($searchContent1, $searchContent2);
    }
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
      <b> Search Results</b> <br/>
      <?php
      $contentSize = sizeof($searchContent);
      if  ($contentSize == 0) {
          printf("No Items Found!<br/>\n");
      } else {
	  printf("<select>\n");
	  $i = 0;
	  while (($i<3) && ($currentIndex < $contentSize)) {
	      generateOptionElement("#". $searchContent[$currentIndex]->getItemNo(),
	      $searchContent[$currentIndex]->getTitle());
	      $generateDescCard[] = $currentIndex;
	      $currentIndex++;
	      $i++;
	  }
      if ($currentIndex < $contentSize) {
          $nextHref = "doSearch.php4?currentIndex=" . 
	  $currentIndex . "&amp;" . getSessionIdString();
	  generateOptionElement($nextHref, "View Next Items");
      }
      printf("</select>\n");
      }
      ?>
    </p>
  </card>

  <?php
  if ($contentSize > 0) {
      // Display the details of each card
      for($i=0; $i<sizeof($generateDescCard); $i++) {
          $itemNo = $searchContent[$generateDescCard[$i]]->getItemNo();
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
      $item = $searchContent[$generateDescCard[$i]];

      printf("%s<br/>\n", $item->getTitle());
      if (strncasecmp($item->getItemType(), 'BOOK', 4) == 0) {
          printf("%s<br/>\n", $item->getAuthor());
      } else {
          printf("%s<br/>\n", $item->getArtist());
      }
          printf("%s<br/>\n", $item->getItemType());
          printf("$$%2.2f\n",$item->getPrice());
      ?>
      </p>
      </card>
      <?php
      } //end for
      } //end if
  ?>
</wml>
