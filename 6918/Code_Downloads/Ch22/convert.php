<?php

$amount = 123456.123;

setlocale("LC_ALL", $locale);
$locale_info = localeconv();

echo(number_format($amount,
                   $locale_info['frac_digits'],
                   $locale_info['mon_decimal_point'],
                   $locale_info['mon_thousands_sep']));

echo("<BR>");

setlocale("LC_ALL", 'da_DK');
$locale_info = localeconv();

echo(number_format($amount,
                   $locale_info['frac_digits'],
                   $locale_info['mon_decimal_point'],
                   $locale_info['mon_thousands_sep']));

?>
