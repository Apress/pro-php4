<?php

// Some initializations 
require_once('timer.php');
require_once('php_info.php'); 
  
$tim = new timer();
$tim->timer_start('total');

$tim->timer_start();
$foo = new php_info();
print('Constructor:'.$tim->timer_stop().'\n');

$tim->timer_start();
$res1 = $foo->phpinf();
print('Method1:'.$tim->timer_stop().'\n');

$tim->timer_start();
$res2 = $foo->multiply();
print('Method2:'.$tim->timer_stop().'\n');
  
echo $res1;
echo $res2;

print('Total execution time:'.$tim->timer_stop('total').'\n');
?>
