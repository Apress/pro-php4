<?php

    header("Content-Type: text/vnd.wap.wml" );
    setSessionHandlers();
?>

<!DOCTYPE wml PUBLIC "-//WAPFORUM//DTD WML 1.1//EN" 
				"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
  <card id="main">
    <p>
      Welcome to the Shopping Mall
      <br />
      <select ivalue="1">
        <option title="LOGN">
	  <onevent type="onpick">
	   <go href="#login"/>
	  </onevent>
	  Sign In
	</option>
	<option title="REG">
          <onevent type="onpick">
	    <go href="#registration"/>
	  </onevent>
	  User Registration
	</option>
      </select>
    </p>
  </card>

  <card id="login">
    <p>
      UserId:
      <input name="userId" title="UserId" type="text"/>
      Password:
      <input name="password" title="Password" type="password"/>
      <do type="accept" label="Submit">
        <go method="post" href="login.php4?userId=$(userId:escape)&amp;password=$(password:escape)"/>
      </do>
    </p>
  </card>

  <card id="registration">
    <p>	
      First Name:
      <input name="fname" type="text" />
      Last Name:
      <input name="lname" type="text" />
      UserId:
      <input name="userId" type="text" />
      Password:
      <input name="password" type="password" />
      Address:
      <input name="address" type="text" />
      City:
      <input name="city" type="text" />
      Country:
      <input name="country" type="text" />
      Zip code:
      <input name="zipCode" type="text" format="*N" />
      Gender
      <select name="gender">
        <option value="Male"> Male </option>
        <option value="Female"> Female </option>
      </select>
      Age:
      <input name="age" type="text" format="*N" />
      EmailId:
      <input name="emailId" type="text" />
      Phone No:
      <input name="phoneNo" type="text" format="NNN NNN NNNN" />
      Card Type:
      <select name="cardType">
        <option value="Visa"> Visa </option>
        <option value="Master"> Master </option>
        <option value="American Express "> American Express </option>
      </select>
      Card Number:
      <input name="cardNumber" type="text" format="NNNN NNNN NNNN NNNN"/>
      Card Expiry Date:
      (mm/dd/yyyy)
      <input name="cardExpiryDate" type="text" format="NN\/NN\/NNNN" />
      <do type="accept">
        <go href="CreateUser.php4?fname=$(fname:escape)&amp;lname=$(lname:escape)&amp;userId=$(userId:escape)&amp;password=$(password:escape)&amp;address=$(address:escape)&amp;city=$(city:escape)&amp;country=$(country:escape)&amp;zipCode=$(zipCode:escape)&amp;gender=$(gender:escape)&amp;age=$(age:escape)&amp;emailId=$(emailId:escape)&amp;phoneNo=$(phoneNo:escape)&amp;cardType=$(cardType:escape)&amp;cardNumber=$(cardNumber:escape)&amp;cardExpiryDate=$(cardExpiryDate:escape)" />
      </do>
    </p>
  </card>
</wml>
