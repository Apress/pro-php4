<?php

include_once("common.php");
if (!isSessionAuthenticated()) {
    sendErrorpage("User session has expired!!. Please login again");
    exit;
}
?>

<html>
  <head>
    <title> Online Storage Application </title>
  </head>
  <body>
    <table width="100%">
      <tr>
        <td width="75%">
          <b> Welcome <?php echo $username ?></b>
	</td> 
	<td width="25%"> 
	  <a href="logout.php">Logout</a>
	</td>
      </tr> 
    </table>
	
    <br> 
    <table valign=top border=1 width=100% cellpadding=2 cellspacing=0>
      <tr>
        <!-- Display the content of the current folder -->
        <td valign=top width=50%>
	  <table valign=top width=100%>
	    <tr bgcolor="#FFFFCC"valign=top nowrap>
	      <th valign=top align=left>Name</th>
	        <th valign=top align=left>File Size </th>
		<th valign=top align=left>Last Modified</th>
            </tr>
            
            <?php
            // Display the contents of the current folder
            $dir = opendir(getAbsolutePath($currentFolder));
            while (($file = readdir($dir)) != null) {
                //Do not display mimeTypes file
                if ($file == "mimeTypes") {
                    continue;
                }

                // Do not Display .
                if ($file == ".") {
                    continue;
                }

                // Do not display .. in the root folder
                if ($currentFolder == $username) {
                    if ($file == "..") {
                       continue;
                    }
                }

                $absoluteFilePath = getAbsolutePath($currentFolder . "/" . $file);

                printf("<tr valgin=top nowrap bgcolor=\"#FFFFFF\">\n");
                printf("<td valign=top align=left>\n");

                if (is_dir($absoluteFilePath)) {
                    printf("<a href=viewfolder.php?fold=%s> %s </a>\n", urlencode($file), $file);
                } else {
                    printf("<a target=_blank href=viewfile.php?file=%s> %s </a>\n", urlencode($file), $file);
                }

                printf("</td>\n");

                printf("<td valign=top align=left>%s</td>\n", filesize($absoluteFilePath));

                $dateArray = getdate(filectime($absoluteFilePath));
                printf("<td valign=top align=left>%s</td>\n", date("m/d/Y h:I:s", filectime($absoluteFilePath)));
                printf("</tr>\n");
            }

            closedir($dir)		
            ?>

          </table>
        </td>
	<!-- Generate html forms for creaing,deleting,renaming and uploading files -->
	<td valgin=top width=50%>
	<!-- Form for creating folder -->
	  <form method="post" action="createfolder.php">
            <table border=0 cellpadding=1 cellspacing=1 width="100%">
	      <tr><td nowrap bgcolor="#FFFFCC"><b> Create Folder </b></td></tr>
	      <tr><td nowrap bgcolor="#FFFFFF"> Folder Name: <input type=text name="foldName"></td></tr>
	      <tr><td nowrap bgcolor="dcdcdc"><input type=submit value="Create Folder"></td></tr>
            </table>
          </form>

          <!-- Form for removing folder/ File -->
	  <form method="post" action="removefolder.php">
	  <table border=0 cellpadding=1 width="100%">
	  <tr>
            <td nowrap bgcolor="#FFFFCC"><b> Remove Folder/File 
              <b> Remove Folder/File</b>
            </td>
          </tr>
	  <tr><td nowrap bgcolor="#FFFFFF"> Select a Folder/File: 
          <select name="foldName">
	    <?php
	    $dir = opendir(getAbsolutePath($currentFolder));
	    while (($file = readdir($dir))) {
	        if (($file != ".") && ($file != "..") && ($file != "mimeTypes")) {
	            printf("<option value=%s>", $file);
	            if (is_dir(getAbsolutePath($currentFolder . "/" . $file))) {
		        printf("<i>%s</i>", $file);
		    } else {
		        printf("%s", $file);
		    }
		    printf("</option>\n");
		}
            }
	    ?>
          </select>
	</td></tr>
        <tr>
          <td nowrap bgcolor="dcdcdc">
            <input  type=submit value="Remove">
          </td>
          </tr>
        </table>
      </form>

      <!-- Form for uploading File -->
      <form method="post" enctype="multipart/form-data" action="uploadfile.php">
        <table border=0 cellpadding=1 width="100%">
	  <tr>
            <td nowrap bgcolor="#FFFFCC">
              <b> Upload File </b>
            </td>
          </tr>
	  <tr>
            <td nowrap bgcolor="#FFFFFF">
            <input name="userfile" type="file">
            </td>
          </tr>
	  <tr>
            <td nowrap bgcolor="dcdcdc">
            <input type="submit" value="Upload">
            </td>
          </tr>
	</table>
      </form>
      </td>
      </tr>
    </table>
  </body>
</html>
