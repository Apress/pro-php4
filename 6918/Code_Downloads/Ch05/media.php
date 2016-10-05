<?php

define("MIN_RATING", 0);
define("MAX_RATING", 5);

// Media.php
class Media 
{
    var $id;
    var $name;
    var $inStock;
    var $price;
    var $rating;

    function Media($id, $name, $inStock, $price, $rating) 
    {
        if ($inStock < 0) $inStock = 0;
        if ($price < 0) $price = 0;
        if ($rating < MIN_RATING) $rating = MIN_RATING;
        if ($rating > MAX_RATING) $rating = MAX_RATING;

        $this->id = $id;
        $this->name = $name;
        $this->inStock = $inStock;
        $this->price = $price;
        $this->rating = $rating;
    }

    function buy() 
    {
        $this->inStock--;
    }

    function display() 
    {
        echo("Name: " . $this->name . "<br>");
        echo("Items in stock: " . $this->inStock . "<br>");
        echo("Price: " . $this->price . "<br>");
        echo("Rating: " . $this->rating . "<br>");
    }

    // more methods
}

?>

