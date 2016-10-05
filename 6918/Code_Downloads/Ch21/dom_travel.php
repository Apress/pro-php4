<?php 

$doc = new_xmldoc("1.0");

$root = $doc->add_root("Recordset");

$one = $root->new_child("Travelpackage", "");

$one->setattr("name", "a");

$one->new_child("Country_name", "Cuba");
$one->new_child("City", "Cayo Coco");
$one->new_child("Resort", "Club Tryp Cayo Coco");
$one->new_child("Resort_rating", "4");
$one->new_child("Resort_typeofholiday", "beach");
$one->new_child("Resort_watersports", "true");
$one->new_child("Resort_meals", "true");
$one->new_child("Resort_drinks","true");

$oneSub = $one->new_child("Package", "");

$oneSubSub = $oneSub->new_child("Package_dateofdep", "5/8/89");
$oneSubSub = $oneSub->new_child("Package_price", "879");

$two = $root->new_child("Travelpackage", "");
$two->setattr("name", "b");

$nodeName = array(
    "Country_name" => "Cuba",
    "City" => "Varadero",
    "Resort" => "Sol Club Paleras",
    "Resort_rating" => "3",
    "Resort_typeofholiday" => "beach",
    "Resort_watersports" => "false",
    "Resort_meals" => "true",
    "Resort_drinks" => "false");

while (list($key,$value) = each($nodeName)) {
    $two->new_child($key, $value);
}

$twoSub = $two->new_child("Package", "");

$twoSubSub = $twoSub->new_child("Package_dateofdep", "5/1/89");
$twoSubSub = $twoSub->new_child("Package_price", "779");

$fp = fopen("travel.xml", "w+");

fwrite($fp, $doc->dumpmem(), strlen($doc->dumpmem()));
fclose($fp);

//Here we open travel.xml and append a third Travelpackage element to it. 

$doc = xmldoc(join("", file("travel.xml")));
$root = $doc->root();
$nodes = $root->children();

$one = $root->new_child("Travelpackage", "");
$one->setattr("name", "c");

$nodeName = array(
    "Country_name" => "Jamacia",
    "City" => "Ocho Rios",
    "Resort" => "Sandles Ocho Rios",
    "Resort_rating" => "3",
    "Resort_typeofholiday" => "beach",
    "Resort_watersports" => "true",
    "Resort_meals" => "true",
    "Resort_drinks" => "true");

while (list($key,$value) = each($nodeName)) {
    $one->new_child($key, $value);
}

$oneSub = $one->new_child("Package", "");

$oneSubSub = $oneSub->new_child("Package_dateofdep", "5/11/89");
$oneSubSub = $oneSub->new_child("Package_price", "679");

$fp = fopen("travel.xml", "w+" );

fwrite($fp, $doc->dumpmem(), strlen($doc->dumpmem()));
fclose($fp);
?>
