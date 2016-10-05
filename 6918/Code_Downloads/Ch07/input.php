<?php

//Handle Input here
//Check if $submit has a value of "Go" - The Validator
if($submit=="Go"){
    //The Processor
    echo("You wrote ".$you_wrote);
    echo("<br>You could have done whatever you want with the input instead");
    exit;
} 

?>

<!-- The Frontend HTML form --> 
<form action="<?php echo $PHP_SELF ?>" method="POST" >
    <p>Input a word <input type="text" size="20" name="you_wrote">
    <input type="submit" name="submit" value="Go"></p>
</form>
