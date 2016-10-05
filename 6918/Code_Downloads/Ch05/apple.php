<?php
// Apple.php
class Apple 
{
    var $isEaten;
    function Apple() 
    {
        global $numberOfApples;
        $numberOfApples++;

        $this->isEaten = false;
    }

    function eat() 
    {
        if (!$this->isEaten()) {
            global $numberOfApples;
            $numberOfApples--;
            $this->isEaten = true;
        }
    }

    function isEaten() 
    {
        return $this->isEaten;
    }

    // Static method
    function count() 
    {
        global $numberOfApples;
        return $numberOfApples;
    }
}

$a1 = new Apple(); // sets $numberOfApples to 1
$a2 = new Apple(); // sets $numberOfApples to 2
$a3 = new Apple(); // sets $numberOfApples to 3

echo(Apple::count() . "<br>"); // outputs 3

$a1->eat(); // sets $numberOfApples to 2
$a2->eat(); // sets $numberOfApples to 1

$a4 = new Apple();// sets $numberOfApples to 2

echo(Apple::count() . "<br>"); // outputs 2

?>
