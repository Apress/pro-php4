<?php

// empdir_functions.php

// Common functions go here
// Avoid multiple includes of this file
if (isset($EMPDIR_FUNCS)) {
    return;
} else {
    $EMPDIR_FUNCS = "true";
}

function generateHTMLHeader($message) 
{
    printf ("<head> <title> Foo Widgets - Employee Directory </title>
             </head>");
    printf("<body text=\"#000000\" bgcolor=\"#999999\" link=\"#0000EE\"
                  vlink=\"#551A8B\" alink=\"#FF0000\">\n");
    printf("<h1>Foo Widgets Employee Directory</h1><br><br>");
    printf("<table cellpadding=\"4\" cellspacing=\"0\" 
                   border=\"0\" width=\"600\">");
    printf("<tr bgcolor=\"#dcdcdc\"><td><font face=\"Arial\"><b>");
    printf("%s</b></font><br></td>", $message);
    printf("<td align=\"right\">");
    printf("</font></td></tr>");
    printf("</table>");
    printf("<br>");
    printf("<br>");
}

function generateFrontPage() 
{
    printf("<form method=\"post\" action=\"empdir_first.php\">");
    printf("<input type=\"submit\" name=\"choice\" value=\"SEARCH\">");
    printf("&nbsp; &nbsp; &nbsp;");
    printf("<input type=\"submit\" name=\"choice\" value=\"ADD\">");
    printf("<br>");
    printf("<br>");
    printf("<ul>");
     printf("<li> Search for employees by clicking <i>SEARCH FOR 
            EMPLOYEE</i> </li>");
     printf("<li> Add new employees (Admin only) by clicking <i>ADD A NEW 
            EMPLOYEE</i> </li>");
     printf("<li> Modify employee details by clicking <i>SEARCH FOR 
            EMPLOYEES</i> first and then choosing the entry to 
            Modify</li>");
     printf("<li> Delete an existing entry (Admin only) by clicking 
            <i>SEARCH FOR EMPLOYEES</i> first and then choosing the entry to 
            Delete</li>");
     printf("</form>");
}

function promptPassword($mail, $ou, $actionScript) 
{
    printf("<form method=\"GET\" action=\"%s\">", $actionScript);
    printf("Admin Password: <input type=\"password\"
            name=\"adminpassword\">&nbsp;");
    printf("<input type=\"hidden\" name=\"mail\" value=\"%s\">", 
            urlencode($mail));
    printf("<input type=\"hidden\" name=\"ou\" value=\"%s\">", 
            urlencode($ou));
    printf("<input type=\"submit\" name=\"submit\" value=\"Submit\">");
    printf("</form>");
}

function displayErrMsg($message)
{
    printf("<blockquote><blockquote><blockquote><h3><font 
            color=\"#cc0000\">%s</font></h3></blockquote>
            </blockquote></blockquote>\n", $message);
}

function connectBindServer($bindRDN = 0, $bindPassword = 0)
{
    global $ldapServer;
    global $ldapServerPort; 
    $linkIdentifier = ldap_connect($ldapServer, $ldapServerPort);

    if ($linkIdentifier) {

        if (!$bindRDN && !$bindPassword) {
            if (!@ldap_bind($linkIdentifier)) { 
                displayErrMsg("Unable to bind to LDAP server !!");
                return 0;
            }
        } else {
            if (!ldap_bind($linkIdentifier, $bindRDN, $bindPassword)) { 
                displayErrMsg("Unable to bind to LDAP server !!");
                return 0;
            }
        }
    } else {
        displayErrMsg("Unable to connect to the LDAP server!!");
        return 0;
    }
    return $linkIdentifier;
}

function createSearchFilter($searchCriteria)
{
    $noOfFieldsSet = 0;
    if ($searchCriteria["cn"]) {
        $searchFilter = "(cn=*" . $searchCriteria["cn"] . "*)";
        ++$noOfFieldsSet;
    }
   
    if ($searchCriteria["sn"]) {
        $searchFilter .= "(sn=*" . $searchCriteria["sn"] . "*)";
        ++$noOfFieldsSet;
    }

    if ($searchCriteria["mail"]) {
        $searchFilter .= "(mail=*" . $searchCriteria["mail"] . "*)";
        ++$noOfFieldsSet;
    } 
  
    if ($searchCriteria["employeenumber"]) {
        $searchFilter .= "(employeenumber=*" .
                          $searchCriteria["employeenumber"] . "*)";
        ++$noOfFieldsSet;
    }

    if ($searchCriteria["ou"]) {
        $searchFilter .= "(ou=*" . $searchCriteria["ou"] . "*)";
        ++$noOfFieldsSet;
    }

    if ($searchCriteria["telephonenumber"]) {
        $searchFilter .= "(telephonenumber=*" . 
            $searchCriteria["telephonenumber"] . "*)";
        ++$noOfFieldsSet;
    }
    if ($noOfFieldsSet >= 2) {
        $searchFilter = "(&" .$searchFilter. ")";
    }
    return $searchFilter;
}

function searchDirectory($linkIdentifier, $searchFilter) 
{
    global $baseDN; 
    $searchResult = ldap_search($linkIdentifier, $baseDN, $searchFilter);
    if (ldap_count_entries($linkIdentifier, $searchResult) <= 0) {
        displayErrMsg("No entries returned from the directory");
        return 0;
    } else { 
        $resultEntries = ldap_get_entries($linkIdentifier, $searchResult);
        return $resultEntries;
   }
}

function printResults($resultEntries) 
{
    printf("<table border width=\"100%%\" bgcolor=\"#dcdcdc\" nosave>\n");
    printf("<tr><td><b>First Name</b></td>
            <td><b>Last Name</b></td>
            <td><b>E-mail</b></td>
            <td><b>Employee #</b></td>
            <td><b>Department</b></td>
            <td><b>Telephone</b></td>
            <td><b>Edit</b></td>
            </tr></b>\n");

    $noOfEntries = $resultEntries["count"];

    for ($i = 0; $i < $noOfEntries; $i++) {
        if (!$resultEntries[$i]["cn"] && !$resultEntries[$i]["sn"])
            continue;
        $mailString = urlencode($resultEntries[$i]["mail"][0]);
        $ouString = urlencode($resultEntries[$i]["ou"][0]);
        printf("<tr><td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>
                <a href=\"empdir_modify.php?mail=%s&ou=%s&firstCall=1\">
                  [Modify]</a>
                <a href=\"empdir_delete.php?mail=%s&ou=%s\">
                  [Delete]</a><td>
                </tr>\n", 
                $resultEntries[$i]["cn"][0],
                $resultEntries[$i]["sn"][0],
                $resultEntries[$i]["mail"][0],  
                $resultEntries[$i]["employeenumber"][0], 
                $resultEntries[$i]["ou"][0], 
                $resultEntries[$i]["telephonenumber"][0],
                $mailString, $ouString,
                $mailString, $ouString);
    }
    printf("</table>\n");
}

function generateHTMLForm($formValues, $actionScript, $submitLabel)
{
    printf("<form method=\"post\" action=\"%s\"><pre>\n", $actionScript);
    printf("First Name:&nbsp;&nbsp;<input type=\"text\" size=\"35\" 
                                    name=\"cn\" value=\"%s\"><br>\n", 
            ($formValues) ? $formValues[0]["cn"][0] : "");
    printf("Last Name:&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"35\" 
                                               name=\"sn\"  
                                               value=\"%s\"><br>\n",
            ($formValues) ? $formValues[0]["sn"][0] : "");
    printf("E-mail:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"text\" 
            size=\"35\" name=\"mail\" value=\"%s\"><br>\n", ($formValues) ? 
            $formValues[0]["mail"][0] : "");
    printf("Employee no.:<input type=\"text\" size=\"35\"  
                                name=\"employeenumber\" 
                                value=\"%s\"><br>\n", 
            ($formValues) ? $formValues[0]["employeenumber"][0] : "");
    printf("Department:&nbsp;&nbsp;<input type=\"text\" size=\"35\" 
                                          name=\"ou\" value=\"%s\"><br>\n", 
            ($formValues) ? $formValues[0]["ou"][0] : "");
    printf("Telephone:&nbsp;&nbsp;&nbsp;<input type=\"text\" size=\"35\" 
            name=\"telephonenumber\" value=\"%s\"><br>\n", ($formValues) ? 
            $formValues[0]["telephonenumber"][0] : "");
    if ($submitLabel == "MODIFY") {
        printf("User Password:&nbsp;&nbsp;&nbsp;&nbsp;
                <input type=\"password\" size=\"35\" 
                       name=\"userpassword\"><br>\n");
    }
    if ($submitLabel == "ADD") {
        printf("Admin Password:&nbsp;&nbsp;&nbsp;&nbsp;
               <input type=\"password\" size=\"35\" 
                      name=\"adminpassword\"><br>\n");
    }
    printf("<input type=\"submit\" value=\"%s\">", $submitLabel);
    printf("</pre></form>");
}

function returnToMain() 
{
    printf("<br><form action=\"empdir_first.php\" method=\"post\">\n");
    printf("<input type=\"submit\" VALUE=\"Click\"> 
            to return to Main Page\n");
}

function closeConnection($linkIdentifier)  
{
    ldap_close($linkIdentifier);
}
?>

