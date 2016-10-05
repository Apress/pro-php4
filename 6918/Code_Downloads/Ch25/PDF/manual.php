<?php

//manual.php

//Create a new PDF object and "open" it as inline
$pdfFile = pdf_new();
pdf_open_file($pdfFile, "");

//Set miscellaneous information
pdf_set_info($pdfFile, "Author", "Devon O'Dell");
pdf_set_info($pdfFile, "Creator", "Devon O'Dell");
pdf_set_info($pdfFile, "Title", "PDFlib Demonstration");
pdf_set_info($pdfFile, "Subject", "Demonstrating the PDFlib");

//Get the entries from the database
$entries = mysql("manual", "SELECT * FROM entries");

//Set up a for loop to grab information for each entry
for($index = 0; $index < mysql_num_rows($entries); $index++) {

    //Grab information for the current row
    $entry = mysql_fetch_array($entries);

    //Start a new page
    pdf_begin_page($pdfFile, 595, 842);

    //Add a bookmark with the name of the entry topic
    pdf_add_bookmark($pdfFile, $entry['topic']);

    //Set the font to 9 point Arial or give an error message
    if ($font = pdf_findfont($pdfFile, "Arial", "winansi", 1)) {
        PDF_setfont($pdfFile, $font, 9);
    } else {
        echo("Font Not Found.");

        pdf_end_page($pdfFile);
        pdf_close($pdfFile);
        pdf_delete($pdfFile);

        exit;
    }

    //Print the content from the database at the specified point
    pdf_show_xy($pdfFile, $entry['content'], 50, 780);

    //End the page inside the loop so that we can begin a new one when
    //we enter the loop again.
    pdf_end_page($pdfFile);
}

//Close the $pdfFile
pdf_close($pdfFile);

//Get ready to output
$pdf = pdf_get_buffer($pdfFile);
$pdfLen = strlen($pdf);

//Send relevant headers
header("Content-type: application/pdf");
header("Content-Length: $pdfLen");
header("Content-Disposition: inline; filename=phpMade.pdf");

//Send the info to the browser
print($pdf);

//Get rid of the object.
pdf_delete($pdfFile);
?>
