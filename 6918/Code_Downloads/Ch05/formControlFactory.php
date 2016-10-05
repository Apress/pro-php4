<?php

include_once("./TextField.php");
include_once("./SubmitButton.php");

// FormControlFactory.php
class FormControlFactory 
{
    function createTextField($name, $value)
    {
        return new TextField($name, $value);
    }
    function createSubmitButton($name, $value) 
    {
        return new SubmitButton($name, $value);
    }
}

$formControlFactory = new FormControlFactory();
$firstNameField = $formControlFactory->createTextField('firstname', 'Ken');
$lastNameField =  $formControlFactory->createTextField('lastname', 'Egervari');
$submitButton =   $formControlFactory->createSubmitButton('submit', 'Submit Name');

?>
