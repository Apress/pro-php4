<?php

//Guessing Game Demonstrates readline extension.
 
$play = "y";

while($play=="y") {
    $correct = false;
    
    //Get options from user
    $max = readline ("Maximum possible value: ");
    $no_of_guesses = readline ("No of guesses: ");
    
    //Generate random number
    srand ((double) microtime() * 1000000);
    $num = floor(rand (0, $max));
    
    for($i=0;$i<$no_of_guesses;$i++) {
        $guess = readline("Guess: ");
    
        if($guess>$num) {
            $message = "Lower";
        } elseif ($guess<$num) {
            $message = "Higher";
        } else {
            echo "\nYou guessed correctly!!\n";
            echo "Well done!  It took you $i goes.\n";
            $correct = true;
            break;
        }
    
        echo $message."\n";
        readline_add_history ($guess);
    }
  
    if($correct != true)
        echo "Sorry, you ran out of guesses!\n";
    while(($play != 'y') &&($play != 'n'))
        $play = strtolower(readline('Play again? [y/n]'));
}
?>

