<?php

dl('php_gtk.'.(strstr(PHP_OS, 'WIN')?'dll':'so')) || die("Unable to load PHP-GTK module\n");

function quit_routine($window)
{
    gtk::main_quit();
}

function hello($button, $window)
{
    print("Hello World!\n");
    $window->destroy();
}
 
$window = &new GtkWindow();
$window->set_border_width(10);
$window->connect('destroy', 'quit_routine');

$button = &new GtkButton('Hello World!');
$button->connect('clicked', 'hello', $window);
$window->add($button);

$tooltip = &new GtkTooltips();
$tooltip->set_tip($button, 'Prints "Hello World!" and vanishes', null);
$tooltip->enable();
 
$window->show_all();
 
gtk::main();
?>
