<?php

//pdfdemo.php

$pdfFile = pdf_new();
PDF_open_file($pdfFile, "");

pdf_set_info($pdfFile, "Author", "Devon O'Dell");
pdf_set_info($pdfFile, "Creator", "Devon O'Dell");
pdf_set_info($pdfFile, "Title", "PDFlib Demonstration");
pdf_set_info($pdfFile, "Subject", "Demonstrating the PDFlib");

pdf_begin_page($pdfFile, 595, 842);

pdf_add_bookmark($pdfFile, "Page 1");

if ($font = pdf_findfont($pdfFile, "Arial", "winansi", 1)) {
    PDF_setfont($pdfFile, $font, 12);
} else {
    echo("Font Not Found.");
    pdf_end_page($pdfFile);
    pdf_close($pdfFile);
    pdf_delete($pdfFile);
    exit;
}

pdf_show_xy($pdfFile, "Sample Text in Arial Font", 50, 780);

pdf_end_page($pdfFile);
pdf_close($pdfFile);

$pdf = pdf_get_buffer($pdfFile);
$pdfLen = strlen($pdf);

header("Content-type: application/pdf");
header("Content-Length: $pdfLen");
header("Content-Disposition: inline; filename=phpMade.pdf");

print($pdf);

pdf_delete($pdfFile);
?>

