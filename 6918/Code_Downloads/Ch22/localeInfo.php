<?php

function localef($amount) 
{
    //Export the values returned by localeconv into the local scope
    extract(localeconv());

    // Start by formatting the unsigned number
    $number = number_format(abs($amount),
                            $frac_digits,
                            $mon_decimal_point,
                            $mon_thousands_sep);

    if ($amount < 0) {
        $sign = $negative_sign;
        //The following statements "extracts" the boolean value as an integer 
        $n_cs_precedes  = intval($n_cs_precedes  == true);
        $n_sep_by_space = intval($n_sep_by_space == true);
        $key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
    } else {
        $sign = $positive_sign;
        $p_cs_precedes  = intval($p_cs_precedes  == true);
        $p_sep_by_space = intval($p_sep_by_space == true);
        $key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
    }

    $formats = array(
        // Currency symbol is after amount
        // No space between amount and sign.
        '000' => '(%s' . $currency_symbol . ')',
        '001' => $sign . '%s ' . $currency_symbol,
        '002' => '%s' . $currency_symbol . $sign,
        '003' => '%s' . $sign . $currency_symbol,
        '004' => '%s' . $sign . $currency_symbol,

        // One space between amount and sign.
        '010' => '(%s ' . $currency_symbol . ')',
        '011' => $sign . '%s ' . $currency_symbol,
        '012' => '%s ' . $currency_symbol . $sign,
        '013' => '%s ' . $sign . $currency_symbol,
        '014' => '%s ' . $sign . $currency_symbol,

        // Currency symbol is before amount
        // No space between amount and sign.
        '100' => '(' . $currency_symbol . '%s)',
        '101' => $sign . $currency_symbol . '%s',
        '102' => $currency_symbol . '%s' . $sign,
        '103' => $sign . $currency_symbol . ' %s',
        '104' => $currency_symbol . $sign . '%s',

        // One space between amount and sign.
        '110' => '(' . $currency_symbol . ' %s)',
        '111' => $sign . $currency_symbol . ' %s',
        '112' => $currency_symbol . ' %s' . $sign,
        '113' => $sign . $currency_symbol . ' %s',
        '114' => $currency_symbol . ' ' . $sign . '%s');

    // We then lookup the key in the above array.
    return sprintf($formats[$key], $number);

}

setlocale("LC_ALL", $locale);
echo(localef(123.45));
?>
