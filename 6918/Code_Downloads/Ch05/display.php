<?php

$mediaItems = array(new Book(…), new Cd(…), new Book(…), new Cd(…));

foreach ($mediaItems as $item) {
    $item->display();
    echo("<br><br>");
}

$mediaItems[] = new ConsoleGame(…);

foreach ($mediaItems as $item) {
    $item->display();
    echo("<br><br>");
}

?>
