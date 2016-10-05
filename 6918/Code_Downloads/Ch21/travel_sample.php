<?php

//Set debug to 0 if you don't want to see all the processing information on the screen
//or to 1 if you do want to see all the processing information on the screen
$debug = "1";
global $debug;
?>

<html>
  <head>
    <title>PRAX Demonstration</title>
  </head>
  <body>
    <?php
    print("<h1>Travel Packages</h1>\n");

    //Include the RAX library
    include("PRAX.php");

    //Display XML
    if ($debug=="1") {
        print("<b>Given the XML:</b> <pre>" . 
               htmlentities(implode("", file("./travel.xml"))) . "</pre>");
    }

    //Create a new RAX object
    $rax = new RAX();

    //Open the XML document
    $rax->openfile("./travel.xml");

    //Select the individual record delimiter, similar table row
    $rax->record_delim = 'Travelpackage';

    //Start parsing the XML document
    $rax->parse();

    //Read the first record
    $rec = $rax->readRecord();

    //Field Names
    if ($debug=="1") {
        $fieldnames = $rec->getFieldnames();
        print("<b>\$rec->getFieldnames()</b>" . "<blockquote>" .  
               join("<br />", $fieldnames) . "</blockquote>");
    }
    //Field Values
    if ($debug=="1") {
        print("<b>\$rec->getFields()</b>" . "<blockquote>" .  
               join("<br />", $rec->getFields()) . "</blockquote>");
    }

    echo("<table cellpadding=\"0\" border=\"0\">\n");

    while ( $rec ) {
        $row = $rec->getRow();
        echo("<tr><td>Country_name</td><td>" . 
              $row["Country_name"] . "</td></tr>\n");
        echo("<tr><td>City</td><td>" . $row["City"] . "</td></tr>\n");
        echo("<tr><td>Resort</td><td>" . $row["Resort"] . "</td></tr>\n");
        echo("<tr><td>Resort_rating</td><td>" . 
              $row["Resort_rating"] . "</td></tr>\n");
        echo("<tr><td>Resort_typeofholiday</td><td>" . 
              $row["Resort_typeofholiday"] . "</td></tr>\n");
        echo("<tr><td>Resort_watersports</td><td>" . 
              $row["Resort_watersports"] . "</td></tr>\n");
        echo("<tr><td>Resort_meals</td><td>" . 
              $row["Resort_meals"] . "</td></tr>\n");
        echo("<tr><td>Resort_drinks</td><td>" . 
              $row["Resort_drinks"] . "</td></tr>\n");
        echo("<tr><td colspan=2><hr></td></tr>\n");
        $rec = $rax->readRecord();
    }
    echo("</table>\n");
    ?>
  </body>
</html>
