<?php
class EngineeringManager extends Manager 
{
    var $engineer;
    function EngineeringManager($firstName, $lastName, $engineerType) 
    {
        Manager::Manager($firstName, $lastName);

        $this->engineer = new Engineer($firstName, $lastName,$engineerType);
    }

    function designProject($project) 
    {
        $this->engineer->designProject($project);
    }

    function getEngineerType() 
    {
        return $this->engineer->getEngineerType();
    }
}

$engineeringManager = new EngineeringManager('Ken', 'Egervari', 'Mechanical');

?>
