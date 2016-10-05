<html>
  <head>
    <title>DOM Travel Packages</title>
  </head>

  <body>
    <h1>Travel Packages</h1>
    <table>
      <?php
      $doc = xmldoc(implode("", file("travel.xml")));
      $context = xpath_new_context($doc);

      $root = $doc->root();

      $expr = "//*";

      if ($path = xpath_eval($context, $expr)) {
          $tmpArray = $path->nodeset;
          while (list() = each($tmpArray)) {
              $i++;
              echo("<tr><td>");
              echo($tmpArray[$i]->name);
              echo("</td><td>");
              echo($tmpArray[$i]->content);
              echo("</td></tr>\n");
          }
      } else {
          echo("expression: $expr, is invalid\n");
      }
      ?>
    </table>
  </body>
</html>
