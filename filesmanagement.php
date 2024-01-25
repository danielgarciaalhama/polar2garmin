<?php


function fileisvalid($i) {
	// Check file size
	# FILESIZE: 1.000.000 == 1MB
	//print_r($_FILES["filetoconvert"]);
	# CC: Max file size to a configuration constant
	if ($_FILES["filetoconvert"]["size"][$i] > 20000000) {
		echo "Sorry, your file is too large.";
		return FALSE;
	}
	
	
	$filename = basename($_FILES["filetoconvert"]["name"][$i]);
	$thisFileType = pathinfo($filename,PATHINFO_EXTENSION);
	# TODO: Modify the implementation to check a list of "accepted" extensions
	if(strtolower($thisFileType) != "tcx") {
		echo "File type not allowed";
		return FALSE;
	}
	
	return TRUE;
	
}

function setFileNewContent ($i, &$content) {
	
	file_put_contents($_FILES["filetoconvert"]["tmp_name"][$i] , $content);
	
}

function getFileOriginalContent ($i) {
	# TODO: The empty result should generate an error message
	$content = "";
	if ($myfile = fopen($_FILES["filetoconvert"]["tmp_name"][$i], "r")) {
		//echo fread($myfile,filesize("webdictionary.txt"));
		$content = fread($myfile,$_FILES["filetoconvert"]["size"][$i]);
		fclose($myfile);
	}
	
	return $content;
}

function getRemoveStops() {
	# CC: Return the condition, the if is not needed
	if (isset($_POST['removestopscb']) && $_POST['removestopscb'] == 'removestops') {
		return 1 ;
	}
	return 0;
}

function transformContent(&$content) {
	# CC: Move the remove of the tags to a generic function like removeTag("TAG"). The code for removing both tags is the same, changing one constant
	$firstauthorpos = strripos($content,"<Author");
	$secondauthorpos = strripos($content,"</Author>");
	
	# Delete first tag
	if ($firstauthorpos != -1 && $firstauthorpos != "" && $firstauthorpos < $secondauthorpos) {
		$newcontent = substr($content,0,$firstauthorpos) . substr($content,$secondauthorpos+9);
	}
	$firstcreatorpos = strripos($newcontent,"<Creator");
	$secondcreatorpos = strripos($newcontent,"</Creator>");
	
	# Delete second tag:
	if ($firstcreatorpos != -1 && $firstcreatorpos != "" && $firstcreatorpos < $secondcreatorpos) {
		$newcontent = substr($newcontent,0,$firstcreatorpos) . substr($newcontent,$secondcreatorpos+10);
		# TODO: Fix the bug: If the file does not contains creator tag, the content is not set (even if the author tag was removed), The following assignation should be moved outside the if
		$content = $newcontent;
	}

	if (getRemoveStops()) {
		# Remove the stops:
		$firststopposition = strripos($newcontent,"</Track><Track>");
		while ($firststopposition != -1 && $firststopposition != "") {
			$newcontent = substr($newcontent,0,$firststopposition) . substr($newcontent,$firststopposition+15);
			$firststopposition = strripos($newcontent,"</Track><Track>");
		}

		$content = $newcontent;
	}
	# CC: removed not used code
			/*$firstauthorpos = strripos($content,"<Author");
			$secondauthorpos = strripos($content,"</Author>");
			$firstcreatorpos = strripos($content,"<Creator");
			$secondcreatorpos = strripos($content,"</Creator>");*/
}
# CC: removed not used code
/*
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}*/
/*input[type="file"]{
	float: left;
    margin: 5px;
    padding: 15px;
    width: 350px;
    height: 50px;
    border-radius: 10px;
    cursor: pointer; cursor: hand;
    border-top-style: none;
    border-left-style: none;    
    color: #fff;
    background: #ADD8E6;
    border-bottom: 3px solid #216895;
    border-right: 3px solid #216895;
  
}*/

?>
