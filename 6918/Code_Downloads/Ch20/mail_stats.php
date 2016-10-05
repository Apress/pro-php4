<?php

set_time_limit(0); // Force this script to run without a time limit

/* -- Variables used in the script -- */
$logfile = "./access.log";
$admin_email = "admin@localhost";
  
function tokenize_line($line)
{   
    $line = preg_replace("/(\[|\]|\")/", "", $line);
    $token = strtok($line, " ");
    while($token)
    {
        $token_array[] = $token;
        $token = strtok(" ");
    }
    $return_array['IP'] = $token_array[0];

    if(!strstr("-",$token_array[2])) 
        $return_array['UserName'] = $token_array[2];

    preg_match("/([\/a-zA-Z0-9]+)[\:]([0-9:]+)/",
               $token_array[3],$date_array);
    $return_array['Date'] = $date_array[1];
    $return_array['Time'] = $date_array[2];

    $return_array['TimeZone'] = $token_array[4];
    $return_array['RequestMethod'] = $token_array[5];
    $return_array['Resource'] = $token_array[6];
    $return_array['HTTPVersion'] = $token_array[7];
    $return_array['StatusCode'] = $token_array[8];
    $return_array['BytesSent'] = $token_array[9];

    return $return_array;
}

$file_contents = file($logfile);

foreach($file_contents as $line)
{
    $info_array = tokenize_line($line);
    $status_code[$info_array['StatusCode']]++;
}

$email = "Summary of codes for todays logs\n\nCode\tCount\n";

foreach($status_code as $code => $count) {
    $email .= "$code:\t$count\n";
}
 
mail ($admin_email, "Summary of weblogs", $email);
?>
