<?php
  
function censorship($buffer) 
{
    return str_replace("foo", "bar", $buffer); 
}

ob_start("censorship");

echo ("This is a foo test of our program\n");
echo ("I can't write foo!\n");
  
ob_end_flush(); 
?>
