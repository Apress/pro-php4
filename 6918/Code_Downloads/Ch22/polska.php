<?php

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

echo(outNumFiles(1) . "<BR>");   // 1 plik.
echo(outNumFiles(3) . "<BR>");   // 3 pliki.
echo(outNumFiles(10) . "<BR>");  // 10 plik�w.
echo(outNumFiles(20) . "<BR>");  // 20 plik�w.
echo(outNumFiles(21) . "<BR>");  // 21 plik�w.
echo(outNumFiles(22) . "<BR>");  // 22 pliki.
echo(outNumFiles(23) . "<BR>");  // 23 pliki.
echo(outNumFiles(27) . "<BR>");  // 27 plik�w.
?>
