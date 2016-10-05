<?php

include("basicOutput.php");
include("danishOutput.php");
include("englishOutput.php");
include("polishOutput.php");

class App 
{
    var $output;

    function App($language) 
    {
        $this->setLanguage($language);
    }

    function setLanguage($new_language) 
    {
        switch ($new_language) {
        case 'da':
            $this->output = new Danish_Output();
            break;
        
        case 'pl':
            $this->output = new Polish_Output();
            break;
    
        default:
            $this->output = new English_Output();
            break;
        }
    }
}
?>

