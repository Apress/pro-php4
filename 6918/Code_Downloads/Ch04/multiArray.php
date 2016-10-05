<?php

$aLanguages = array(
    "Slavic" => array("Russian", "Polish", "Slovenian"),
    "Germanic" => array("Swedish", "Dutch", "English"),
    "Romance" => array("Italian", "Spanish", "Romanian")
);

foreach ($aLanguages as $sKey => $aFamily) {
    // Print the name of the language family:
    echo(
        "<h2>$sKey</h2>\n" .
        "<ul>\n" // Start the list
    );

    // Now list the languages in each family:
    foreach ($aFamily as $sLanguage) {
        echo("\t<li>$sLanguage</li>\n");
    }

    // Finish the list:
    echo("</ul>\n");
}

?>

