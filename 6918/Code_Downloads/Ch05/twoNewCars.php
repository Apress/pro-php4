<?php

$car1 = new Car();
$car2 = new Car();
$cars = array();
for ($i = 0; $i < 10; $i++) {
    $cars[$i] = new Car();
}
$carHasStarted = $car1->start($myKey);

if ($carHasStarted) echo("Car has started.");
$car1->stop();

?>
