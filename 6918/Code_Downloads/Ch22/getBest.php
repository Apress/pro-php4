<?php

function getBestLanguage($avail_lang) 
{
    $accept_lang = explode(', ', $GLOBALS['HTTP_ACCEPT_LANGUAGE']);
  
    while (list($key, $lang) = each($accept_lang)) {
        if (in_array($lang, $avail_lang)) {
          return $lang;
        }
    }
    return reset($avail_lang);
}
?>

