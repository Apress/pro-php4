<?php

// This test class displays the time taken in executing a function 
// containing phpinfo() and another function multiplying a large number.

class php_info
{
    // Constructor
    function php_info()
    {
    }
    // Method 1: containing the builtin function phpinfo()
    function phpinf()
    {
        phpinfo();
    }
    // Method 2: multiplying large numbers
    function multiply()
    {
        $multiplied=10000*10000*10000*10000;
    }
}
?>
