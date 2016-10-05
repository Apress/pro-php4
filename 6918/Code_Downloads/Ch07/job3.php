<?php

$html_block = "<script language='javascript'>

function jobForm_Validator(f) 
{
    if (f.elements[0].value.length < 3) {
        alert('You must type your name and it should be atleast least 3 character long');
    f.elements[0].focus();
    return(false);
}

</script>
    <title>Job Application</title>
  </head>
  <body bgcolor=#FFFFFF>
    <h1 align=center>Job Application</h1>
";

// Regex to match script tags
$search = array ("'<script[^>]*?>.*?</script>'si", 
// Regex to match html tags "'<[^>]*>'si");     

$replace = array ("", //Replace with null
                  "");//Replace with null

$plain_text = preg_replace ($search, $replace, $html_block);

echo($plain_text);

?>
