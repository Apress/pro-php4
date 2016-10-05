<?php
// Book.php
class Book extends Media 
{
    var $isbn;
    var $author;
    var $numberOfPages;

    function Book($id, $name, $inStock, $price, $rating,$isbn, $author, $numberOfPages)
    {
        // It's important to call the parent constructor first, and 
        // then set any members after it's been initialized

        $this->Media($id, $name, $inStock, $price, $rating);
        $this->isbn = $isbn;
        $this->author = $author;
        $this->numberOfPages = $numberOfPages;
    }

    function display() 
    {
        Media::display();

        echo("ISBN: " . $this->isbn. "<br>");
        echo("Author: " . $this->author. "<br>");
        echo("Number of Pages: " . $this->numberOfPages. "<br>");
    }

    // methods
}

?> 
