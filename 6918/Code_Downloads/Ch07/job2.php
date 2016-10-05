<?php

//Begin by including the library
include("oohforms.inc");

//Create the form and its elements here

$f = new form; // create a form object

// Full Name
$f->add_element(array("name"=>"name",
    "type"=>"text",
    "size"=>"20",
    "minlength"=>"4",
    "length_e"=>"You must type your name and it should be at least 4 characters long",
    "valid_e"=>"Your name cannot have numerals.",
    "valid_regex"=>"^([a-zA-Z ])*$" ));

// E-Mail
$f->add_element(array("name"=>"email",
    "type"=>"text",
    "size"=>"20",
    "minlength"=>"1",
    "length_e"=>"You must enter a valid e-mail address",
    "valid_e"=>"Syntax error in e-mail address.",
    "valid_regex"=>"^[-a-zA-Z0-9._]+@[-a-zA-Z0-9]+(\.[-a-zA-Z0-9]+)+$"));

// Address
$f->add_element(array("name"=>"address",
    "type"=>"textarea",               
    "rows"=>3,
    "cols"=>30,
    "value"=>""));

// Gender radio button
$f->add_element(array("name"=>"gender",
    "type"=>"radio",
    "value"=>"Male"));

// Send e-mail check box
$f->add_element(array("name"=>"email_me",
    "type"=>"checkbox",
    "value"=>"Y",
    "checked"=>1));

$c = array("Select a City","Nagpur","Mumbai","Bangalore","Kolkatta");

$f->add_element(array("name"=>"pref_cities",
    "type"=>"select",
    "options"=>$c,
    "minlength"=>"1",
    "size"=>1,
    "valid_e"=>"Please select a preferred city of work"));

// Submit
$f->add_element(array("name"=>"submit",
    "type"=>"submit",
    "value"=>"Submit"));

//Check for submission and validate data
if (isset($submit)) {
    //See if there are any errors in data
    if ($err = $f->validate()) {
        $f->load_defaults();
    } else { 
        // Handle the data here if there are no errors
        $f->load_defaults();
        $f->freeze();
// Some code to put these values into a database will go here
        $err="Success!";
    }
}

//Render the form
$f->start('jobForm','','','','jobForm');

?>

<p>Items marked with <font color="#FF0000">* </font>
  <font color="#000000"> are compulsory</font>
</p>
  <div align="center"><center><table border="1" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td width="25%"><font color="#FF0000">*</font>
        Your Full Name
      </td>
      <td width="75%"><?php $f->show_element("name"); ?></td>
    </tr>
    <tr>
      <td><font color="#FF0000">*</font>Your E-Mail Address</td>
      <td><?php $f->show_element("email"); ?></td>
    </tr>
    <tr>
      <td width="25%">Your Address</td>
      <td width="75%"><?php $f->show_element("address"); ?></td>
    </tr>
    <tr>
      <td>Gender</td>
      <td>
        <?php $f->show_element("gender","Male"); ?>Male
        <?php $f->show_element("gender","Female"); ?>Female
      </td>
    </tr>
    <tr>
      <td>Would like e-mail notification?</td>
      <td><?php $f->show_element("email_me") ?></td>
    </tr>
    <tr>
      <td><font color="#FF0000">*</font>City where I can work</td>
      <td><?php $f->show_element("pref_cities"); ?></td>
    </tr>
  </table>
  </center></div><p align="center">
  <?php 
  if($err!="Success!"){
      $f->show_element("submit");
  }
  ?>
</p>

<?php
$f->finish();
?>
