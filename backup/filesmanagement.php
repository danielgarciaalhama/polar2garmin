<?php


function fileisvalid($i) {
	// Check file size
	# FILESIZE: 1.000.000 == 1MB
	//print_r($_FILES["filetoconvert"]);
	if ($_FILES["filetoconvert"]["size"][$i] > 20000000) {
		echo "Sorry, your file is too large.";
		return FALSE;
	}
	
	$filename = basename($_FILES["filetoconvert"]["name"][$i]);
	$thisFileType = pathinfo($filename,PATHINFO_EXTENSION);
	
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
	$content = "";
	if ($myfile = fopen($_FILES["filetoconvert"]["tmp_name"][$i], "r")) {
		//echo fread($myfile,filesize("webdictionary.txt"));
		$content = fread($myfile,$_FILES["filetoconvert"]["size"][$i]);
		fclose($myfile);
	}
	
	return $content;
}

function transformContent(&$content) {
	$firstauthorpos = strripos($content,"<Author");
	$secondauthorpos = strripos($content,"</Author>");
	
	# Delete first tag
	if ($firstauthorpos != -1 && $firstauthorpos < $secondauthorpos) {
		$newcontent = substr($content,0,$firstauthorpos) . substr($content,$secondauthorpos+9);
	}
	$firstcreatorpos = strripos($newcontent,"<Creator");
	$secondcreatorpos = strripos($newcontent,"</Creator>");
	
	# Delete second tag:
	if ($firstcreatorpos != -1 && $firstcreatorpos < $secondcreatorpos) {
		$newcontent = substr($newcontent,0,$firstcreatorpos) . substr($newcontent,$secondcreatorpos+10);
		$content = $newcontent;
	}
	
			/*$firstauthorpos = strripos($content,"<Author");
			$secondauthorpos = strripos($content,"</Author>");
			$firstcreatorpos = strripos($content,"<Creator");
			$secondcreatorpos = strripos($content,"</Creator>");*/
}
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