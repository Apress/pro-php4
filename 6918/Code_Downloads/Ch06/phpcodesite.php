<?php

/* 
 * phpCodeSite - http://phpcodesite.phpedit.com/
 * Author      : Sébastien Hordeaux
 * Licence     : GNU Public Licence
 * Version     : 1.0
 */

/*
 * History
 *   01/30/2001 : Initial release.
 *
 *   04/01/2001 : 1.0
 *        Added CS_SendObject, CS_RenderVar, CS_AddSeparator, CS_Checkpoint, CS_CleanSession.
 *        Fixed CS_SendArray, CS_SendVar.
 */

/*
 * ToDo
 *
 * - Turn it into a global object instead of XX functions.
 * - Build a client App which will receive trace infos (=> fsockopen, ... instead of echo).
 * -
 */

CS_SetEnabled(TRUE);

// ------------------------------------------------------------- PRIVATE
$CS_Step = 0; // Start without increment

/*
 * Add a level to the reported items
 * @scope private
 */
function CS_IncStep()
{
    global $CS_Step;
    $CS_Step++;
}


/*
 * Remove a level to the reported items
 * @scope private
 */
function CS_DecStep()
{
    global $CS_Step;
    $CS_Step--;
    if($CS_Step < 0)
        $CS_Step = 0;
}

/*
 * Log an item
 * @scope private
 */
function CS_Log($msg)
{
    global $CS_Step;
    for($i = 0; $i < $CS_Step; $i++)
        CS_WriteIndent();
    CS_Write($msg);
}

/*
 * Write data to the target output
 */
function CS_Write($str)
{
    global $CS_Enabled;
    if($CS_Enabled)
        echo $str;
}

/*
 *
 * @scope private
 */
function CS_WriteEOL()
{
    CS_Write("\n");
}

/*
 * Write an indent block
 * @scope private
 */
function CS_WriteIndent()
{
    CS_Write("|   ");
}

/*
 *
 * @scope private
 */
function CS_RenderVar($var)
{
    switch(gettype($var)){
        case "boolean":
            CS_Write($var?"TRUE":"FALSE");
            break;
        case "integer":
        case "double":
            CS_Write($var);
            break;
        case "string":
            CS_Write("\"".htmlentities($var)."\"");
            break;
        case "array":
            CS_WriteArray($var);
            break;
        case "object":
            CS_Write("Object(".get_class($var).")");
            break;
        case "NULL":
            CS_Write("NULL");
            break;
        default:
            CS_Write("[Unknown type]");
    }
}

/*
 * @scope private
 */

function CS_WriteArray($array)
{
    CS_Write("Array(");
    if(count($array) > 0)
        CS_WriteEOL();
    CS_IncStep();
    while(list($key, $value) = each($array)){
        CS_Log("");
        CS_RenderVar($key);
        CS_Write(" => ");
        CS_RenderVar($value);
        CS_WriteEOL();
    }
    if(count($array) > 0)
        CS_Log(")");
    else
        CS_Write(")");
    CS_DecStep();
}

// ------------------------------------------------------------- PUBLIC

/*
 * Switch between Enabled/Disabled mode
 */
function CS_SetEnabled($state)
{
    global $CS_Enabled;
    $CS_Enabled = $state;
    CS_Write($CS_Enabled?"<pre>":"</pre>");
    flush();
}

/*
 * Beginning a new function/method
 */
function CS_EnterMethod($methodName)
{
    CS_Log("--> $methodName");
    CS_WriteEOL();
    CS_IncStep();
}

/*
 * Exit a function/method
 */
function CS_ExitMethod($methodName)
{
    CS_DecStep();
    CS_Log("<-- $methodName");
    CS_WriteEOL();
}

/*
 * Log a note
 */
function CS_SendNote($note)
{
    CS_Log("[N] $note");
    CS_WriteEOL();
}

/*
 * Send a simple message
 */
function CS_SendMessage($msg)
{
    CS_Log("[M] $msg");
    CS_WriteEOL();
}

/*
 * Log an error
 */
function CS_SendError($msg)
{
    CS_Log("<b>[E] $msg</b>");
    CS_WriteEOL();
}

/*
 * Log a sql query
 */
function CS_SendSQL($query)
{
    CS_Log("[SQL] $query");
    CS_WriteEOL();
}

/*
 * Log a variable
 */
function CS_SendVar($value, $varName = "")
{
    CS_Log("[L] ");
    if(!empty($varName))
        CS_Log("$varName = ");
    CS_RenderVar($value);
    CS_WriteEOL();
}


/*
 * Log an array
 * @depreciated Use CS_SendVar instead
 */
function CS_SendArray($array, $arrayStr = "")
{
    CS_SendVar($array, $arrayStr);
}

/*
 *
 */
function CS_SendObject($object, $objectName = "", $showMethods = FALSE)
{
    global $CS_Step;
    $className = get_class($object);
    // parent class
    $parentCaption = get_parent_class($object);
    if(!empty($parentCaption))
        $parentCaption = " extends ".$parentCaption;
    CS_Log("|| \$".$objectName." = class ".$className.$parentCaption."{");
    CS_WriteEOL();
    // class variables
    $oVars = get_object_vars($object);
    if(is_array($oVars))
        while(list($varName, $varValue) = each($oVars)){
            CS_Log("||   var \$$varName = ");
            CS_RenderVar($varValue);
            CS_Write(";");
            CS_WriteEOL();
        }
    // class method
    if($showMethods){
        $oMethods = get_class_methods($className);
        if(is_array($oMethods))
            while(list(, $method) = each($oMethods)){
                CS_Log("||   function $method(){}");
                CS_WriteEOL();
            }
    }else{
        CS_Log("||   [Methods Skipped]");
        CS_WriteEOL();
    }
    CS_Log("|| }");
    CS_WriteEOL();
}

/*
 *
 */
function CS_AddSeparator()
{
    CS_Write("----------------------------------------------------------");
    CS_WriteEOL();
}

/*
 *
 * Ex:
 *  CS_Checkpoint(__FILE__, __LINE__); or CS_Checkpoint();
 */
function CS_Checkpoint($file = "", $line = 0)
{
    global $CS_Checkpoint;
    if(empty($file))
        $ID = "";
    else $ID = " [$file - $line]";
    if(!isset($CS_Checkpoint[$ID])) $CS_Checkpoint[$ID] = 0;
    $CS_Checkpoint[$ID]++;
    CS_Log("[C] Checkpoint$ID (".$CS_Checkpoint[$ID].")");
    CS_WriteEOL();
}

/*
 * Log all global variables
 */
function CS_DisplayInputData()
{
    global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS,
        $HTTP_SERVER_VARS, $HTTP_ENV_VARS, $HTTP_SESSION_VARS;
    CS_AddSeparator();
    CS_SendArray($HTTP_GET_VARS, "HTTP_GET_VARS");
    CS_SendArray($HTTP_POST_VARS, "HTTP_POST_VARS");
    CS_SendArray($HTTP_COOKIE_VARS, "HTTP_COOKIE_VARS");
    CS_SendArray($HTTP_SESSION_VARS, "HTTP_SESSION_VARS");
//    Uncomment if you want to see all...
//
//    CS_SendArray($HTTP_SERVER_VARS, "HTTP_SERVER_VARS");
//    CS_SendArray($HTTP_ENV_VARS, "HTTP_ENV_VARS");
//    CS_SendArray($GLOBALS, "GLOBALS");
    CS_AddSeparator();
}

/*
 * Unregister all session variables
 */
function CS_CleanSession()
{
    global $HTTP_SESSION_VARS;
    foreach($HTTP_SESSION_VARS as $key => $value)
        session_unregister($key);
}
?>

