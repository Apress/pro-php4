<html>
  <head>
    <title>DOM Travel Packages</title>
  </head>

  <body>
    <h1>Travel Packages</h1>
    <table>
      <?php
      $doc = xmldoc(join("", file("travel.xml")));
      $context = xpath_new_context($doc);

      $root = $doc->root();

      $var = array("a","b"); 

      for ($x = 0; $x < count($var); $x++) {
          $path = xpath_eval($context, 
                       "//Travelpackage[@name=\"$var[$x]\"]/Country_name");
          $tmpArray = $path->nodeset;

          echo("<tr><td>");
          echo($tmpArray[0]->name);
          echo("</td><td><a href=\"#\">");
          echo($tmpArray[0]->content);
          echo("</a></td></tr>\n");

          $path = xpath_eval($context, 
                        "//Travelpackage[@name=\"$var[$x]\"]/*");
          $tmpArray = $path->nodeset;
          while (list() = each($tmpArray)) {
              $i++;
              echo("<tr><td>");
              echo($tmpArray[$i]->name); 
              echo("</td><td>");
              echo($tmpArray[$i]->content);
              echo("</td></tr>\n");
          }
          $i=0;

          $path = xpath_eval($context, 
                        "//Travelpackage[@name=\"$var[$x]\"]/Package/*");
          $tmpArray = $path->nodeset;
          echo("<tr><td>");
          echo($tmpArray[0]->name);
          echo("</td><td>");
          echo($tmpArray[0]->content);
          echo("</td></tr>\n");
          echo("<tr><td>");
          echo($tmpArray[1]->name);
          echo("</td><td>");
          echo($tmpArray[1]->content);
          echo("</td></tr>\n");
          echo("<tr><td colspan=2><hr></td></tr>\n");
      }
      ?>
    </table>
  </body>
</html>
