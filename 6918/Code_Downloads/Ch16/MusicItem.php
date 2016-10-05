<?php

include_once ("Item.php");

class MusicItem extends Item 
{
    var $title;
    var $artist;

    function MusicItem($itemNo, $itemType, $price, $title, $artist)
    {
        $this->Item($itemNo, $itemType, $price);
	$this->title = $title;
	$this->artist = $artist;

    }

    function getTitle() 
    {
        return $this->title;
    }

    function getArtist() 
    {
        return $this->artist;
    }
}
?>
