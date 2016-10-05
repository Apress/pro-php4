<?php

class Polish_Output extends Basic_Output 
{
    function outNumFiles($count) 
    {
        if ($count == 0) {
            return "Nie ma plik�w.";
        } elseif ($count == 1) {
            return "1 plik.";
        } elseif ($count <= 4) {
            return "$count pliki.";
        } elseif ($count <= 21) {
            return "$count plik�w.";
        } else {
            $last_digit = substr($count, -1);
            if ($last_digit >= 2 && $last_digit <= 4) {
                return "$count pliki.";
            } else {
                return "$count plik�w.";
            }
        }
    }
}
?>
