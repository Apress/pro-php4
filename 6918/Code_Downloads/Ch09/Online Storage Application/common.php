<?php	
$rootDirectory="d:\\online-storage";
$userProfileDir = "d:\\online-storage\\profiles";
$fileSeparator="\\";

// This method returns the absolute path of the folder
function getAbsolutePath($fold) 
{
    global $rootDirectory, $fileSeparator;
    $arrayStr = split("/", $fold);
    $folderName = $rootDirectory;
    for($i=0; $i < sizeof($arrayStr); $i++) {
        $folderName = $folderName . $fileSeparator . $arrayStr[$i];
    }
    return $folderName;
}

// Create an anchor elemrnt
function makeAnchorElement($href, $text) 
{
    $str="<a href=".$href."> ". $text. "</a>";
    sprintf($str,"<a href=\"%s\"> %s </a>", $href, $text);
    return $str;
}

// Create Folder
function createFolder($currFolder, $foldName) 
{
    return mkdir(getAbsolutePath($currFolder."/".$foldName), 0700);
}

// Delete folder - recursively
function deleteFolder($currFolder, $foldName) 
{
    global $fileSeparator;
    if (($dir = opendir(getAbsolutePath($currFolder . "/" . $foldName))) < 0) {
        return $dir;
    }
    while (($file = readdir($dir)) != null) {
        $absFilePath = getAbsolutePath($currFolder . "/" . $foldName) . $fileSeparator . $file;
	if (is_dir($absFilePath)) {
	    if (($file != ".") && ($file != "..")) {
	        if (($res = deleteFolder($currFolder . "/" . $foldName, $file)) < 0) {
		    return $res;
		}
	    }
        } else {
	    if (($res = deleteFile($currFolder . "/" . $foldName, $file)) < 0) {
	        return $res;
	    }
	}
    } 
    closedir($dir);
				
    return rmdir(getAbsolutePath($currFolder."/".$foldName));
}
		
// Delete file
function deleteFile($currFolder, $fileName) 
{
    return unlink(getAbsolutePath($currFolder."/".$fileName));
}

// Sends the error page
function sendErrorPage($mesg) 
{
    printf("<html>");
    printf("<head></head>");
    printf("<body>");
    printf("<h1>%s</h1>", $mesg);
    printf("</body>");
    printf("</html>");
}

function isSessionAuthenticated() 
{
    global $isAuthenticated;
    session_start();
    if (session_is_registered("isAuthenticated") && $isAuthenticated) {
        return true;
    } else {
	return false;
    }
}
?>
