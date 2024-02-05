<?php


function fileIsValid($i) {
	// Check file size
	if ($_FILES["filetoconvert"]["size"][$i] > MAX_FILE_SIZE) {
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

}

function processFiles() {

	if (!isset($_FILES['filetoconvert'])) {
		return;
	}

	$files = array_filter($_FILES["filetoconvert"]["tmp_name"]);
	
	$total = count($files);

	# Check if the files are valid:
	for( $i=0 ; $i < $total ; $i++ ) {
		# Check if the file is valid:
		# TODO: fileIsValid will report the error, so in case is not valid, no message will be shown here.
		if (!fileIsValid($i)) {
			echo "The file " . $_FILES["filetoconvert"]["name"][$i] . " is not valid";
			exit();
		}
	}
	
	if ($total == 1) {
		# Get the filecontent:
		$content = getFileOriginalContent(0);
		if ($content === "") {
			echo "The file can't be readed";
		} else {
			# Convert the Polar content to Garmin content:
			# CC: Move testing data to a proc like: getTestingContent and before the if
			//$content = '<Name>Correr</Name><Extensions></Extensions></Plan></Training><Creator xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Device_t"><Name>Polar M400</Name><UnitId>0</UnitId><ProductID>22</ProductID><Version><VersionMajor>1</VersionMajor><VersionMinor>8</VersionMinor><BuildMajor>0</BuildMajor><BuildMinor>0</BuildMinor></Version></Creator></Activity></Activities><Author xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Application_t"><Name>Polar Connect</Name><Build><Version><VersionMajor>0</VersionMajor><VersionMinor>0</VersionMinor></Version></Build><LangID>EN</LangID><PartNumber>XXX-XXXXX-XX</PartNumber></Author></TrainingCenterDatabase>';
	
			# file content have all data, delete problematic content:
			transformContent($content);
			# Return the content to the user:
			header("Content-type: text/plain");
			header("Content-Disposition: attachment; filename=" . basename($_FILES["filetoconvert"]["name"][0]));
			echo $content;
			exit();
		}
			
	} else {
		
		$zip = new ZipArchive(); // Load zip library
		$zip_name = "convertedfiles" . ".zip"; // Zip name
		if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) {
			// Opening zip file to load files
			$error .= "* Sorry ZIP creation failed at this time";
		}
		
		for( $i=0 ; $i < $total ; $i++ ) {
			# Get the filecontent:
			$content = getFileOriginalContent($i);
			if ($content === "") {
				echo "The file can't be readed";
			} else {
				# Convert the Polar content to Garmin content:
				# CC: Move testing data to a proc like: getTestingContent
				//$content = '<Name>Correr</Name><Extensions></Extensions></Plan></Training><Creator xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Device_t"><Name>Polar M400</Name><UnitId>0</UnitId><ProductID>22</ProductID><Version><VersionMajor>1</VersionMajor><VersionMinor>8</VersionMinor><BuildMajor>0</BuildMajor><BuildMinor>0</BuildMinor></Version></Creator></Activity></Activities><Author xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Application_t"><Name>Polar Connect</Name><Build><Version><VersionMajor>0</VersionMajor><VersionMinor>0</VersionMinor></Version></Build><LangID>EN</LangID><PartNumber>XXX-XXXXX-XX</PartNumber></Author></TrainingCenterDatabase>';
				
				# file content have all data, delete problematic content:
				transformContent($content);
				$zip->addFromString($_FILES["filetoconvert"]["name"][$i], $content);
				
			}
			
			
		}
		
		$zip->close();
		
		if(file_exists($zip_name))
		{
			
			// push to download the zip
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'.$zip_name.'"');
			readfile($zip_name);
			// remove zip file if exists in temp path
			unlink($zip_name);
		}
		
	}
}

?>
