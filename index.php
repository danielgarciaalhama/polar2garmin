<?php

# PENDING:
# -Add errors and warning as floating windows


$inipath = php_ini_loaded_file();

include("p2gconfig.php");
include("filesmanagement.php");


processFiles();

?>

<!DOCTYPE html>
<html>
<head><title>Convert files online</title>
<meta name="description" content="This page is useful to convert geospatial files, for example to avoid the uncompatibility of Polar files into Garmin system.">
<link rel="stylesheet" type="text/css" href="styles.css">
<script type="text/javascript">

function fileChanged(){
	var files=document.getElementById('filetoconvert').files;
	
	// CC: Move each case of the if to an independent function
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

function showElement(elemId) {
	var myItem = document.getElementById(elemId);
	myItem.style.display="block";
}

function hideElement(elemId) {
	var myItem = document.getElementById(elemId);
	myItem.style.display="none";
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
	<input id="submitbutton" class="inputgenerical" type="submit" value="Convert" ><br>
	<input type="checkbox" id="removestopscb" name="removestopscb" value="removestops">
	<label for="removestopscb">Remove pauses</label>&nbsp;&nbsp;&nbsp;&nbsp;<div class="tooltip"><img src="help.png"/>
	<span class="tooltiptext">Sometimes, when importing a TCX training that contains pauses, the graphs (pace, hearth rate...) and the moving time are not well calculated. 
	Select this option to try to correct this issue.</span>
	</div>
</form><br>
<label id="filenamelabel"> </label>

<div id="removestopshelp" style="display:none">
	Sometimes, when importing a TCX training that contains pauses, the graphs (pace, hearth rate...) and the moving time are not well calculated. 
	Select this option to try to correct this issue.
</div>
<div id="textlayer">


	<br><br>This web is a trying to maintain the file compatibility between some diferents systems, to make easier the use of multiple systems and to improve the user experience. I will add more files extensions. I hope you find it useful.<br><br>
	<b>Last changes:</b><br><br>
	<b>&nbsp;&nbsp;December 15, 2023:</b>
	<ul>
	<li>Fixed an issue with some resolutions that made the conversion's formulary being rendered out of the screen.</li>
	</ul>
	<b>&nbsp;&nbsp;May 27, 2020:</b>
	<ul>
	<li><b><font color="blue">Added the "Remove pauses" option. </font></b>Always I had problems with the graphs while uploading TCX trainings with pauses to Garmin. I added this option to fix this issue. I cannot assure that all the problems will be fixed but I expect that it helps.</li>
	<li>If after this change the file conversion doesn't work fine, feel free to use the previous implementation of the website (March 7, 2020) in the following link: <a href="http://www.polar2garmin.com/backup/">Polar2Garmin previous version</a></li>
	</ul>
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
	<li>Who knows...</li>
	</ul>
	</div>
	
</div>
</div>

</body>
</html>
