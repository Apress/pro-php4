<?php

//admin.php

if (!$submit) { 
?>

  <html>
    <head><title>Admin.php</title></head>
    <body>
      <form action="<?php echo($PHP_SELF); ?>" method="POST">
        Topic: <input type=text maxlength=100 name=topic><br>
        Content:<br>
        <textarea rows=80 cols=25 name=content></textarea><br>
        <input type=submit name=submit>
      </form>
    </body>
  </html>

<?php
} else {
    if (@mysql("manual", "INSERT INTO entries (id, topic, content) VALUES (NULL, '$topic', '$content')")) {
        echo("Successfully added information into database");
    } else {
        echo("Error: " . mysql_error());
   }
}
?>
