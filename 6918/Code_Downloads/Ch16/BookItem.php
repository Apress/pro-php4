<?php

include_once("Item.php");

class Book_Item extends Item 
{
    var $title;
    var $author;

    function Book_Item($itemNo, $itemType, $price, $title, $author) 
    {
        $this->Item($itemNo, $itemType, $price);
	$this->title = $title;
	$this->author = $author;
    }

    function getTitle() 
    {
        return $this->title;
    }

    function getAuthor() 
    {
        return $this->author;
    }
}
?>
