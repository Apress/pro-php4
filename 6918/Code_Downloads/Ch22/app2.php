<?php

class App 
{
    var $output;
    var $avail_lang;

    //The constructor initializes the array of available languages, and chooses the best one. 
    function App() 
    {
        $this->avail_lang = array('en', 'da', 'pl');
        $this->setLanguage($this->getBestLanguage());
    }

    // Based on the array $avail_lang of available languages, and the browsers Accept-Language header, this function will return the language code for the best language. 
    function getBestLanguage() 
    {
        $accept_lang = explode(', ', $GLOBALS['HTTP_ACCEPT_LANGUAGE']);

        while (list($key, $lang) = each($accept_lang)) {
            if (in_array($lang, $this->avail_lang)) {
                return $lang;
            }
        }
        return reset($this->avail_lang);
    }

    // Switches to another language by constructing a new output-object.
    function setLanguage($new_language = '') 
    {
        switch ($new_language) {
        case 'en':
            $this->output = new English_Output();
            break;
       
        case 'da':
            $this->output = new Danish_Output();
            break;
       
        case 'pl':
            $this->output = new Polish_Output();
            break;
       
        default:
            $this->setLanguage($this->getBestLanguage);
            break;
        }
    }
}

// Base-class for all output-classes.
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

    // A generic outNumFiles() function.
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

    // Returns the characterset for the translation.
    function getCharset() 
    {
        return $this->strings['charset'];
    }
}

class English_Output extends Basic_Output 
{
    // The constructor initializes the $strings array with the correct
    // characterset. The other strings are in basic_output.
    function English_Output() 
    {
        $this->strings = array(
            'charset' => 'ISO-8859-1');
    }
}

class Danish_Output extends Basic_Output 
{
    function Danish_Output() 
    {
        $this->strings = array(
            'No files.' => 'Ingen filer.',
            '1 file.'   => '1 fil.',
            '%s files.' => '%s filer.',
            'charset'   => 'ISO-8859-1');
    }
}

class Polish_Output extends Basic_Output 
{
    function Polish_Output() 
    {
        $this->strings = array(
            'charset' => 'ISO-8859-2');
    }

    function outNumFiles($count) 
    {
        if ($count == 0) {
            return "Nie ma plików.";
        } elseif ($count == 1) {
            return "1 plik.";
        } elseif ($count <= 4) {
            return "$count pliki.";
        } elseif ($count <= 21) {
            return "$count plików.";
        } else {
            $last_digit = substr($count, -1);
            if ($last_digit >= 2 && $last_digit <= 4) {
                return "$count pliki.";
            } else {
                return "$count plików.";
            }
        }
    }
}

$obj = new App();

header('Content-Type: text/html; charset=' . $obj->output->getCharset());
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
             "http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php
    echo($obj->output->getCharset()); ?>">
    <title>My App</title>
  </head>
  <body>

  <?php
  echo("<p>" . $obj->output->outNumFiles(7) . "</p>\n");
  ?>

  </body>
</html>
