<?php

class Basic_Output 
{
    var $strings;

    function _($string)
    {
        if (isset($this->strings[$string])) {
            return $this->strings[$string];
        } else {
            return $string;
        }
    }

    function gettext($string)
    {
        return $this->_($string);
    }

    function outNumFiles($count) 
    {
        if ($count == 0) {
            return $this->gettext("No files.");
        } elseif ($count == 1) {
            return $this->gettext("1 file.");
        } else {
            return sprintf($this->gettext("%s files."), $count);
        }
    } 
}
?>
