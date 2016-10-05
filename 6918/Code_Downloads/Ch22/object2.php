<?php

include("basicOutput.php");
include("englishOutput.php");
include("danishOutput.php");
include("polishOutput.php");

$obj = new English_Output();
echo($obj->outNumFiles(3)); // 3 files.

echo("<BR>");

$obj = new Danish_Output();
echo($obj->outNumFiles(5)); // 5 filer.

echo("<BR>");

$obj = new Polish_Output();
echo($obj->outNumFiles(7)); // 7 plików.

?>
