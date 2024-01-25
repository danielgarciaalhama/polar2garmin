<?php

$inipath = php_ini_loaded_file();
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include("filesmanagement.php");
if (isset($_FILES['filetoconvert'])) {
	$files = array_filter($_FILES["filetoconvert"]["tmp_name"]);
	
	$total = count($files);
	if ($total == 1) {
		# Check if the file is valid:
		$isvalid = fileisvalid(0);
		if ($isvalid) {
	//		echo "file valid";
			# Get the filecontent:
			$content = getFileOriginalContent(0);
			if ($content === "") {
				echo "The file can't be readed";
			} else {
				# Convert the Polar content to Garmin content:
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
			echo "The file is not valid";
		}
	} else {
		
		# Check if the files are valid:
		for( $i=0 ; $i < $total ; $i++ ) {
			# Check if the file is valid:
			$isvalid = fileisvalid($i);
			if (!$isvalid) {
				echo "The file " . $_FILES["filetoconvert"]["name"][$i] . " is not valid";
				exit();
			}
		}
		
		
		$zip = new ZipArchive(); // Load zip library
		$zip_name = "convertedfiles" . ".zip"; // Zip name
		if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE) {
			// Opening zip file to load files
			$error .= "* Sorry ZIP creation failed at this time";
		}
		
		for( $i=0 ; $i < $total ; $i++ ) {
	//		echo "file valid";
			# Get the filecontent:
			
			$content = getFileOriginalContent($i);
			if ($content === "") {
				echo "The file can't be readed";
			} else {
				# Convert the Polar content to Garmin content:
				//$content = '<Name>Correr</Name><Extensions></Extensions></Plan></Training><Creator xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Device_t"><Name>Polar M400</Name><UnitId>0</UnitId><ProductID>22</ProductID><Version><VersionMajor>1</VersionMajor><VersionMinor>8</VersionMinor><BuildMajor>0</BuildMajor><BuildMinor>0</BuildMinor></Version></Creator></Activity></Activities><Author xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="Application_t"><Name>Polar Connect</Name><Build><Version><VersionMajor>0</VersionMajor><VersionMinor>0</VersionMinor></Version></Build><LangID>EN</LangID><PartNumber>XXX-XXXXX-XX</PartNumber></Author></TrainingCenterDatabase>';
				
				# file content have all data, delete problematic content:
				transformContent($content);
				
				
				$zip->addFromString($_FILES["filetoconvert"]["name"][$i], $content);
				
				# There are several files, store the new content:
				# setFileNewContent($i,$content);
				
			}
			
			
		}
		
		$zip->close();
		
		if(file_exists($zip_name))
		{
			// push to download the zip
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'.$zip_name.'"');
			readfile($zip_name);
			// remove zip file is exists in temp path
			unlink($zip_name);
		}
		
	}
	
	
	
	 
} 


?>
<!DOCTYPE html>
<html>
<head><title>Convert files online</title>
<meta name="description" content="This page is useful to convert geospatial files, for example to avoid the uncompatibility of Polar files into Garmin system.">
<link rel="stylesheet" type="text/css" href="styles.css">
<script type="text/javascript">

function fileChanged(){
	var files=document.getElementById('filetoconvert').files;
	
	if (files.length > 0) {
		document.getElementById('filelabel').style.backgroundColor = "#219568";
		document.getElementById('filelabel').style.borderBottomColor = "#219568";
		document.getElementById('filelabel').style.borderRightColor = "#219568";
		if (files.length == 1) {
			var basename = files[0].name;
			if (basename.lastIndexOf('\\') != -1) {
				basename = basename.substring(basename.lastIndexOf('\\') + 1);
			} else if (basename.lastIndexOf('/') != -1) {
				basename = basename.substring(basename.lastIndexOf('/\\/') + 1);
			}
			document.getElementById('filenamelabel').innerHTML = "Selected file to convert: " + basename;
		} else {
			document.getElementById('filenamelabel').innerHTML = files.length + " files selected";
		}
		
		document.getElementById('submitbutton').style.background = "#A03030";
		document.getElementById('selecttypes').style.background = "#216895";
		document.getElementById('selecttypes').disabled = false;
		
	} else {
		document.getElementById('filelabel').style.backgroundColor = "#216895";
		document.getElementById('filelabel').style.borderBottomColor = "#216895";
		document.getElementById('filelabel').style.borderRightColor = "#216895";
		document.getElementById('filenamelabel').innerHTML = "";
		document.getElementById('submitbutton').style.background = "#BBBBBB";
		document.getElementById('selecttypes').style.background = "#BBBBBB";
		document.getElementById('selecttypes').disabled = true;
	}
    
}

</script>
</head>
<body>
<div id="centerall2">
	<div id="formlayer">
<form action="" id="mainform" method="post" enctype="multipart/form-data">
	<input class="inputgenerical" type="file" id="filetoconvert" name="filetoconvert[]" onchange="fileChanged();" accept=".tcx" hidden="1" required multiple>
	<label id="filelabel" class="filelabel" for="filetoconvert"><br>Select File</label>
	<select id="selecttypes" class="inputgenerical" disabled="disabled">
		<option value="tcx2tcx">TCX to TCX</option>
	</select>
	<input id="submitbutton" class="inputgenerical" type="submit" value="Convert" >
</form><br>
<label id="filenamelabel"> </label>

<div id="textlayer">


	<br><br>This web is a trying to maintain the file compatibility between some diferents systems, to make easier the use of multiple systems and to improve the user experience. I will add more files extensions. I hope you find it useful.<br><br>
	<b>Last changes:</b><br><br>
	<b>&nbsp;&nbsp;March 7, 2020:</b>
	<ul>
	<li><b><font color="red">Bug fixed:</font></b> If the input file extension was in uppercase, the file was rejected.</li>
	</ul>
	<b>&nbsp;&nbsp;July 19, 2019:</b>
	<ul>
	<li><b><font color="blue">Added the possibility to select several files in the file selection window.</font></b> With this improvement it is possible to upload several files simultaneously. If multiple files are selected, all files will be downloaded as a zip file. If you are like me and you copy your trainings once a week or every other week you will find that this will speed up the whole process.</li>
	<li>The ads banner has been removed. Almost everybody uses an ad-blocker and has no sense maintain the ads. I hope this will improve the user experience.</li>
	</ul>
	<b>Next changes:</b>
	<ul>
	<li>(internal) Read TCX and GPX and transform to an internal XML type.</li>
	<li>(internal) Write the internal XML type into TCX and GPX files</li>
	<li>Allow transformations between TCX and GPX types</li>
	<li>Allow GoogleEarth types</li>
	<li>Show tracks into a map with height, heart rate and speed charts</li>
	<li>Create a training system that allows you control your trainings and save all the data in the datastore that you want (Google Drive, Dropbox, Garmin, Polar, this web, your own computer...) I still don't think about it.</li>
	</ul>
	</div>
</div>
</div>

</body>
</html>
