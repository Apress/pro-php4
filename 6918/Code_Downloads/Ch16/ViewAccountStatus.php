<?php

include_once ("Common.php");
include_once ("User.php");
include_once ("Transaction.php");
include_once ("BookItem.php");
include_once ("MusicItem.php");

setSessionHandlers();
if (!headers_sent()) {
    header("Content-Type: text/vnd.wap.wml" );
}

checkSessionAuthenticated();

$generateDescCard = null;

if (!$userOrders) {
    $currentIndex = 0;

    $userOrders = $user->getTransactions();
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
      <b> Account Status </b> <br/>
      <b> Balance: </b> <?php printf("$$%2.2f\n", $user->getAccountBalance()) ?> <br/>
      <?php
      if ($currentIndex < sizeof($userOrders)) {
          printf("<select>\n");
          for($i=0; (($i < 3) && ($currentIndex < sizeof($userOrders))); 
          $i++) {
              $transaction = $userOrders[$currentIndex];
              $item = $transaction->getItem();
	      generateOptionElement("#card".$userOrders[$currentIndex]->getOrderNo(),
																	$item->getTitle());
	      $generateDescCard[] = $currentIndex;
	      $currentIndex++;
          }
          if ($currentIndex < sizeof($userOrders)) {
              $nextHref = "ViewAccountStatus.php?currentIndex=" .
              $currentIndex . "&amp;" . getSessionIdString();
              generateOptionElement($nextHref, "View Next Items");
           }
      printf("</select>\n");
      }
      ?>
    </p>
  </card>

  <?php
  // Display the details of each card
  for($i=0; $i<sizeof($generateDescCard); $i++) {
      $itemNo = $userOrders[$generateDescCard[$i]]->getOrderNo();
  ?>
  <card id="card<?php echo $itemNo?>" >
    <do type="accept" label="BACK">
      <go href="#main" />
    </do>
    <do type="options" label="HOME">
      <go href="AppMain.php?<?php echo getSessionIdString() ?>"/>
    </do>
    <p>
      <?php
      $item = $userOrders[$generateDescCard[$i]]->getItem();
      printf("%s<br/>\n", $item->getTitle());
      echo("itemtype:");
      if (strncasecmp($item->getItemType(), 'BOOK', 4) == 0) {
          printf("%s<br/>\n", $item->getAuthor());
      } else {
          printf("%s<br/>\n", $item->getArtist());
      }

      printf("Quantity: %s<br/>\n", $userOrders[$generateDescCard[$i]]->getQuantity());
      printf("Date:%s<br/>\n", 
      convertDateFromMysqlFormat($userOrders[$generateDescCard[$i]]->getDate()));
      printf("Status:%s<br/>\n", $userOrders[$generateDescCard[$i]]->getStatus());
      ?>
    </p>
  </card>

  <?php
  } //end for
  ?>

</wml>
