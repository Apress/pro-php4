<?php

include("handler.php");

session_start();
session_register($count);

$count++;

// Increment our count variable in which we store
// the accesses to the page

if ($action == "destroy") {
    session_destroy();

} elseif ($action == "gc") {
    // We actually force garbage collection here by
    // directly calling our custom handler that does so.

    $maxlife = get_cfg_var("session.gc_maxlifetime");
    session_gc($maxlife);

} elseif (!$action) {
    echo("No action specified<br>");
} else {
    echo("Cannot do $action with the session handlers<br>");
}

?>

<html>
  <head> 
   <title>Session Test Functions</title>
  </head>
   <body>Action: <b><?=$action?></b><br>
     Count: <b><?=$count?></b><br><p>

     <form action=<?=$PHP_SELF?> method="POST">
       <table border=0>
         <tr>
           <td>Action:</td>
	   <td>
	     <select name="action">
	       <option value="destroy">Destroy</option>
	       <option value="gc">Force Garbage Collection</option>
	     </select>
	   </td>
         </tr>
         <tr>
           <td></td>
           <td><br><input type="submit"></td>
         </tr>
       </table>
       <center>Hit refresh to increment the counter</center>
     </form>
  </body>
</html>
