<?php

include_once("Common.php");
include_once("User.php");
setSessionHandlers();
header("Content-Type: text/vnd.wap.wml" );
checkSessionAuthenticated();

$musicShopContent = null;
$bookShopContent = null;
$searchContent = null;
$userOrders = null;
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card id="main">
    <p>
      Welcome <?php echo $user->getUserId() ?>
      <select ivalue="1">
      <option title="SRCH">
        <onevent type="onpick">
          <go href="#search"/>
        </onevent>
        Search 
      </option>
      <option title="BOOK">
        <onevent type="onpick">
	  <go href="ViewBookShop.php?<?php echo getSessionIdString() ?>"/>
	</onevent>
	Book Shop
      </option>
      <option title="MUSC">
        <onevent type="onpick">
	  <go href="ViewMusicShop.php?<?php echo getSessionIdString() ?>"/>
	</onevent>
	Music Shop
      </option>

      <option title="DISP">
        <onevent type="onpick">
	  <go href="DisplayCart.php?<?php echo getSessionIdString() ?>"/>
	</onevent>
	Display Cart 
      </option>

      <option title="COUT">
	<onevent type="onpick">
	  <go href="CheckOut.php?<?php echo getSessionIdString() ?>"/>
	</onevent>
	Check Out
      </option>

      <option title="ASTAT">
        <onevent type="onpick">
	  <go href="ViewAccountStatus.php?<?php echo getSessionIdString() ?>"/>
	</onevent>
	Account Status
      </option>

      <option title="LOFF">
        <onevent type="onpick">
	  <go href="Logout.php?<?php echo getSessionIdString()?>"/>
	</onevent>
	Logout
      </option>
      </select>
    </p>
  </card>

  <card id="search">
    <do type="options" label="HOME">
      <go href="#main" />
    </do>
    <p>
      Items can be searched for by title and Author/Performer of Book/CD/Cassette
    </p>
    <do type="accept">
      <go href="#searchForm" />
    </do>
  </card>

  <card id="searchForm">
    <do type="options" label="HOME">
      <go href="#main" />
    </do>
    <p>
    Enter Search Text:
    <input name="searchText" type="text"/>
    Select Search Criteria:
    <select name="searchType" ivalue="1">
      <option value="Book by Title">Book by Title</option>
      <option value="Book by Author">Book by Author</option>
      <option value="Music Album by Title">Music Album by Title</option>
      <option value="Music Album by Artist">Music Album by Artist</option>
      <option value="Entire Database">Entire Database</option>
    </select>
    </p>
    <do type="accept">
      <go href="doSearch.php?searchText=$(searchText:escape)&amp;searchType=$(searchType:escape)&amp;<?php echo getSessionIdString() ?>" />
    </do>
  </card>
</wml>
