<?php

$debug = 1; # Set to 1 to turn on debugging or 0 to turn off debugging.
?>

<html>
  <head>
    <title>SAX Demonstration</title>
  </head>
  <body>
    <h1>Travel Packages</h1>
    <table border="0" cellpadding="0">

      <?php
      //define the location of the XML document 
      $file = "./travel.xml";
      
      //use the 'current' vars to keep track of which tag/attribute 
      //the parser is currently processing 
      $currentTag = "";
      $currentAttribs = "";

      function startElement($parser, $name, $attribs) 
      {
          global $currentTag, $currentAttribs;
          $currentTag = $name;
    
          $currentAttribs = $attribs;
          //define the HTML to use for the start tag.
          switch ($name) {
          case "Recordset":
              break;
    
          case "Travelpackage":
              while (list ($key, $value) = each ($attribs)) {
                  echo("<tr><td>$key: $value</td></tr>\n");
              }
              break;

          case "package":
              break;

          default:
              echo("<tr><td>$name</td><td>\n");
              break;
          }
      }

      function endElement($parser, $name) 
      {
          global $currentTag;
          //output closing HTML tags
          switch ($name) {
          case "Travelpackage":
              echo("<tr><td colspan=\"2\"><hr></td></tr>\n");
              break;

          default:
              echo("</td></tr>\n");
              break;
          }
          //clear current tag variable
          $currentTag = "";
          $currentAttribs = "";
      }

      //process data between tags
      function characterData($parser, $data) 
      {
          global $currentTag;
          //add HTML tags to the values
          switch ($currentTag) {
          case "Country_name":
              echo("<a href=\"#\">$data</a>\n");
              break;
          default:
              echo($data);
              break;
          }
      }

     //initialize parser
     $xmlParser = xml_parser_create();

      $caseFold =	 xml_parser_get_option($xmlParser, 
                                        XML_OPTION_CASE_FOLDING);
      $targetEncoding = xml_parser_get_option($xmlParser, 
                                              XML_OPTION_TARGET_ENCODING);

      if ($debug > 0) {
          echo("Debug is set to: $debug<br>\n");
          echo("Case folding is set to: $caseFold<br>\n");
          echo("Target Encoding is set to: $targetEncoding<br>\n");
      }
      //disable case folding
      if ($caseFold == 1) {
          xml_parser_set_option($xmlParser, XML_OPTION_CASE_FOLDING, false);
      }

      //set callback functions
      xml_set_element_handler($xmlParser, "startElement", "endElement");
      xml_set_character_data_handler($xmlParser, "characterData");

      //open XML file
      if (!($fp = fopen($file, "r"))) {
          die("Cannot open XML data file: $file");
      }

      //read and parse data
      while ($data = fread($fp, 4096)) {
          //error handling
          if (!xml_parse($xmlParser, $data, feof($fp))) {
              die(sprintf("XML error: %s at line %d",
                          xml_error_string(xml_get_error_code($xmlParser)),
                          xml_get_current_line_number($xmlParser)));
              xml_parser_free($xmlParser);
          }
      }
      //free up the parser
      xml_parser_free($xmlParser);
      ?>
    </table>
  </body>
</html>
