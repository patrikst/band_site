<?php
//  ================================
//	File	: movie.php	
//    Parameters:
//
//	action  - view
//			- add
//			- edit
//			- update
//			- delete
//  ================================
// Start session
session_start (); // ???

// GET THE ARRAYS
$givers2 = $_POST['a_giver'];
$receivers2 = $_POST['a_receiver'];
for($i=1;$i<=count($givers2);$i++)
	if ($debug) echo "<li> $i. $givers2[$i]";
for($i=1;$i<=count($receivers2);$i++)
	if ($debug) echo "<li> $i. $receivers2[$i]";

// $checkBoxes2 = $_GET['checkBoxes'];
//for($i=0; $i < count($checkBoxes2); $i++){
//    echo "Selected " . $checkBoxes2[$i] . "<br/>";
//}
$ArrayList = array("_GET", "_POST", "_SESSION", "_COOKIE", "_SERVER"); // "_GET"
/**/
foreach($ArrayList as $gblArray)
{
   $keys = array_keys($$gblArray);
   foreach($keys as $key)
   {
       $$key = trim(${$gblArray}[$key]);
   //    echo "<li> $keys - $key - $$key";
   }
	foreach($_GET as $key=>$value)
	{
    	if( $MY_PHP_PARAM_LIST == "")
       		$MY_PHP_PARAM_LIST = "?$key=$value";
       	else
       		$MY_PHP_PARAM_LIST = $MY_PHP_PARAM_LIST . "&" . "$key=$value";
	}
	foreach($_POST as $key=>$value)
	{
    	if( $MY_PHP_PARAM_LIST == "")
       		$MY_PHP_PARAM_LIST = "?$key=$value";
       	else
       		$MY_PHP_PARAM_LIST = $MY_PHP_PARAM_LIST . "&" . "$key=$value";
       //	if ($key == "lyrics")
		// echo "<li> $key=$value, ";
	}
}

// Inkludera globala variabler
// och standardfunktioner
$i = 1;
// $inc_debug=1;
$include_file = "db_include.php";
if($inc_debug) echo ". Next file $include_file...";
$success = include ("../common/include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;
$include_file = "util_php_include.php";
$success = include ("../common/include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;

// include ("include/util_php_include.php");
//include ("include/util_error_report_include.php");
$include_file = "error_report_include.php";
if($inc_debug) echo ". Next file $include_file...";
$success = include ("../common/include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;
//include ("include/util_buttons_include.php");
$include_file = "util_buttons_include.php";
if($inc_debug) echo ". Next file $include_file...";
$success = include ("../common/include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;
// === Non-dependent include files
// include ("include/util_logger_include.php");	
$include_file = "util_logger_include.php";
if($inc_debug) echo ". Next file $include_file...";
$success = include ("../common/include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;
// include ("include/util_file_include.php");
$include_file = "util_file_include.php";
$success = include ("../common/include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;
$i++;
$success = include ("../common/include/util_menu_include.php");
if(!$success) echo "Unable to include $i <b>util_menu_include.php</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - util_menu_include.php";
$i++;
$success = include ("../common/include/util_css_include.php");
if(!$success) echo "Unable to include $i <b>util_css_include.php</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - util_css_include.php";
// BAND MEMBER INCLUDE
$success = include ("../common/include/util_band_member_include.php");
if(!$success) echo "Unable to include $i <b>util_band_member_include</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - util_lyrics_include";
$i++;
// LYRICS INCLUDE 
$success = include ("../common/include/util_lyrics_include.php");
if(!$success) echo "Unable to include $i <b>util_lyrics_include</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - util_lyrics_include";
$i++;
// === LYRICS INSTANCE 
$success = include ("../common/include/util_lyrics_instance_include.php");
if(!$success) echo "Unable to include $i <b>util_lyrics_instance_include</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - util_lyrics_instance_include.php";
$i++;
// === BAND COMMON INCLUDE
$success = include ("../common/include/util_band_common_include.php");
if(!$success) echo "Unable to include $i <b>util_band_common_include</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - util_band_common_include.php";
$i++;
	include ("../common/include/util_calendar_include.php");
	include ("../common/include/util_band_gig_include.php");
	/*	*/	
	include ("../common/include/util_band_lyrics_include.php");
	include ("../common/include/util_band_repertoir_include.php");
	include ("../common/include/util_band_rehersal_include.php");
	include ("../common/include/util_band_video_include.php");
// =======================
//	LOCAL HTML
// =======================
// ======== GROCERIES INCLUDE
/*
$include_file = "groceries_include.php";
if($inc_debug) echo ". Next file $include_file...";
$success = include ("include/$include_file"); // WORKING!
if(!$success) echo "Unable to include $i <b>$include_file</b>"; else if($inc_debug) echo "<li> $i.th include file = OK! - $include_file";
$i++;
*/

// =================================
//   S T A R T   O F   S E S S I O N
// =================================

if (isset ($REMOTE_USER))
{
  $username = $REMOTE_USER;

  // Register session variables
  session_register ('username');
  session_register ('user_id');
  session_register ('proj_nr');

}
// Registrera sessionsvariabler.
session_register ('username');
session_register ('user_id');
session_register ('proj_nr');

// =================================
//	E N D   O F   S E S S I O N
// =================================

connect_to_db ();

$THE_FILE_AND_PATH = __FILE__;		// For logging purposes
uf_get_file_info($debug, $THE_FILE_AND_PATH, &$PHP_FILE_NAME, &$filePath, &$fileSuffix, &$fileNameNoSuffix, &$fileType, &$fileFolder, &$fileURL);
$PHP_WITH_PARENT_FOLDER = $fileFolder . "/" . $PHP_FILE_NAME;
ul_manage_logging($REMOTE_ADDR, $_SERVER['HTTP_USER_AGENT'], $PHP_WITH_PARENT_FOLDER);
$isPhone = ul_is_phone($_SERVER['HTTP_USER_AGENT']);
$DEVICE = ul_get_device($_SERVER['HTTP_USER_AGENT']);
?> 

<!DOCTYPE HTML>
<html>
<head>
<!-- ======================= JAVASCRIPT ============================= -->
<?php
	$bandID = 1;
	ubdc_get_title_data($debug, $targetPHP, $bandID, &$bandName, $action, &$pageTitle, &$addAction, &$editAction);
	um_print_band_head($bandName);
	ubdc_get_band_data($debug, $level, $bandID, &$bandName, &$bandDB, &$bandLogo, &$menuLogo, &$iconFileName);	
	ubdc_print_page_icon($debug, $iconFileName);
?>
<?PHP
	// groc_print_javascript_header();
?>
</head>

<body  bgcolor=#ffffff text="#000000" link=#00aa00 vlink=#aa00aa>
<font face=helvetica>
<!-- ==================================================================== -->
<?php
	// ====== 
	$targetPHP = "index.php";
	$MainDb		= "Lyrics";
	$dbase 		= "bands";  
	$tableName  = "new_lyrics";
	$bResponsive = 1;
	$bChords = 1;
	um_print_band_menu($debug, $targetPHP, $bandID);
	ucss_page_start();
	if ($debug) echo "<li> Lyrics  = '$lyrics'";
//	ubdc_get_band_data($debug, $level, $bandID, &$bandName, &$bandDB, &$titleImage, &$menuLogo, &$iconFileName);	
//	$titleImage = "../common/images/icons/heo_icon.png";
	// $addAction = "$PHP_FILE_NAME?action=print_add_item_form";

	// $pageTitle = "<a href=$PHP_FILE_NAME style=text-decoration:none>$pageTitle</a>";
	ubdc_get_band_data($debug, $level, $bandID, &$bandName, &$bandDB, &$titleImage, &$menuLogo, &$iconFileName);	


	switch($action)
	{
		// =========================
		// 		LYRICS MANAGEMENT
		// =========================		
		// === PLAYBACK
		case view_playback:
		case add_playback:
		case edit_playback:
		case update_playback:
		case delete_playback:
		// === VIDEOS
		case view_videos:
		case add_video:
		case edit_videos:
		case update_videos:
		case delete_videos:
		// === SPOTIFY
		case view_spotify:
		case add_spotify:
		case edit_spotify:
		case update_spotify:
		case delete_spotify:
		// === MP3
		case view_mp3:
		case add_mp3:
		case edit_mp3:
		case update_mp3:
		case delete_mp3:
		// === MIDI
		case view_midi:
		case add_midi:
		case edit_midi: 
		case update_midi:
		case delete_midi:
		// === MUSIC SHEET
		case view_music_sheet:
		case add_music_sheet:
		case edit_sheet:
		case update_sheet:
		case delete_sheet:
		// === LYRICS
		case view_lyric:
		case edit_lyric:
		case update_lyric:
			ucssr_print_lyrics_title($debug, $pageTitle, $backAction, $addAction, $titleImage, $editAction, $valign, $iconHeight, 0, $targetPHP, $bandID, $songID, $PREVIOUS_ACTION, $theKey, $dbase, $tableName, $action);
			break;
		// =========================
		// 		BAND MANAGEMENT
		// =========================		
		//  REP + TILLGÄNGLIGHET
		case view_rehersals:
		case add_availability:
		case edit_rehersal_availability:
		case print_rehersal_availability:
			ucssr_print_band_mgmt_title($debug, $pageTitle, $backAction, $addAction, $titleImage, $editAction, $valign, $iconHeight, 0, $targetPHP, $bandID, $songID, $PREVIOUS_ACTION, $theKey, $dbase, $tableName, $action, $nofDays, $userID, $userIDedit);
			break;
		case view_band_video:
		case view_start_page:
		case search:
		case "":
			ucssr_print_page_title($debug, $pageTitle, $backAction, $addAction, $titleImage, $editAction, $valign, $iconHeight, 1, $targetPHP, $search_value);
			break;
		case view_repertoir:	
			break;
		default:
			ucssr_print_page_title($debug, $pageTitle, $backAction, $addAction, $titleImage, $editAction, $valign, $iconHeight, 0, $targetPHP);
	}

	// HANDLE ACTION
	if($bandID== ""|| $bandID <= 0)
	{
		up_error("index.php: BandID invalid. exiting");
		return;
	}
	else
	{
		// echo "<li> BandID-$bandID - fontsize = $fontSize";	
	}	
	// =====================================================
	// FILE UPLOAD MANAGEMENT - During ADD and UPDATE
	// =====================================================
	echo $_SESSION ['userlogin'];
	if ($action == "do_add_mp3" || $action == "update_mp3")
	{
	   	$filename 		= $_FILES['mp3_file']['name'] ;
	   	$sourceFile 	= $_FILES['mp3_file']['tmp_name'];
		$AltFolderName = "mp3";
		$FileNamePrefix = "";
		$name = "mp3";
	}
	else if ($action == "do_add_playback" || $action == "update_playback")
	{
	   	$filename 	= $_FILES['playback_file']['name'] ;
	   	$sourceFile 	= $_FILES['playback_file']['tmp_name'];
		$AltFolderName = "mp3";
		$name = "playback";
		$FileNamePrefix = "PB_$instrument_";
	}
	else if ($action == "do_add_music_sheet" || $action == "update_music_sheet")
	{
	   	$filename 	= $_FILES['music_sheet_file']['name'] ;
	   	$sourceFile 	= $_FILES['music_sheet_file']['tmp_name'];
		$AltFolderName = "music_sheets";
		$name = "music_sheets";
		//echo "<li> Music Sheet: Filename = $filename...uploading";
	}
	else if ($action == "do_add_midi" || $action == "update_midi")
	{
		if ($debug) up_note( "settings file parameters.");
	   	$filename 	= $_FILES['midi_file']['name'] ;
	   	$sourceFile 	= $_FILES['midi_file']['tmp_name'];
		$AltFolderName = "mp3";
		$name = "midi";
		//echo "<li> Midi: Filename = $filename ...uploading";
	}
	// =========================
	// UPLOAD FILES SECTION - BAND MANAGEMENT
	// =========================
	else if ($action == "upload_repertoir_pdf")
	{
		// $debug = 1;
	   	$short_filename 	= $_FILES['short_pdf']['name'] ;
	   	$short_sourceFile 	= $_FILES['short_pdf']['tmp_name'];

		if($short_filename != "")
		{
			//$subFolderName = "$name";
			$subFolderName = $category;
			
			if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
			if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $short_filename, $AltFileName,  $short_sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
			{
				$short_document = $relativePath;
				if($debug) up_note("<b>The file '$short_filename' was uploaded to : , $relativePath</b>", 2);
				if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
				// echo "<img src= $actualURL><br>";
			}
			else
			{
				up_error("index.php - <b>Upload Failure</b> - $errorMessage");
				$image = "";
			}
			if ($debug) up_note( "Uploading file....done!");
		}
		else
		{
			if($debug) up_info("index.php: No file given");
		}
		$debug = 0;
		$long_filename 		= $_FILES['long_pdf']['name'] ;
	   	$long_sourceFile	= $_FILES['long_pdf']['tmp_name'];

		if($long_filename != "")
		{
			//$subFolderName = "$name";
			$subFolderName = $category;
			
			if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
			if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $long_filename, $AltFileName,  $long_sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
			{
				$long_document = $relativePath;
				if($debug) up_note("<b>The file '$long_filename' was uploaded to : , $relativePath</b>", 2);
				if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
				// echo "<img src= $actualURL><br>";
			}
			else
			{
				up_error("ub_band_mgmt.php - <b>Upload Failure</b> - $errorMessage");
				$image = "";
			}
			if ($debug) up_note( "Uploading file....done!");
		}
		else
		{
			if($debug) up_info("ub_band_mgmt.php: No file given");
		}
		$debug = 0;
	}
	else if($action == "add_travel" || $action == "update_travel")
	{
		//$debug = 1;
	   	$filename 		= $_FILES['travelDocument']['name'] ;
	   	$sourceFile 	= $_FILES['travelDocument']['tmp_name'];

		if($filename != "")
		{
			//$subFolderName = "$name";
			$subFolderName = $category;
			if ($userID)
			{
				ubd_get_band_member_data($userID, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture);
			
				$AltFileName = $firstName . "_" . $lastName . $userID . "-" . $date . "_gID-" . $gigID. "_tID-" . $travelID;
			}
			if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
			if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
			{
				$travelDocument = $relativePath;
				if($debug) up_note("<b>The file '$filename' was uploaded to : , $relativePath</b>", 2);
				if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
				// echo "<img src= $actualURL><br>";
			}
			else
			{
				up_error("ub_band_mgmt.php - <b>Upload Failure</b> - $errorMessage");
				$travelDocument = "";
			}
			if ($debug) up_note( "Uploading file....done!");
		}
		else
		{
			if($debug) up_info("ub_band_mgmt.php: No file given");
		}
		$debug = 0;
	}
	else if ($action == "update_bandmember")
	{
		// $debug = 1;
	   	$image_filename 	= $_FILES['image']['name'] ;
	   	$image_sourceFile 	= $_FILES['image']['tmp_name'];

		if($image_filename != "")
		{
			//$subFolderName = "$name";
			$subFolderName = $category;
			
			if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
			if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $image_filename, $AltFileName,  $image_sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
			{
				$image = $relativePath;
				if($debug) up_note("<b>The file '$image_filename' was uploaded to : , $relativePath</b>", 2);
				if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
				// echo "<img src= $actualURL><br>";
			}
			else
			{
				up_error("ub_band_mgmt.php - <b>Upload Failure</b> - $errorMessage");
				$image = "";
			}
			if ($debug) up_note( "Uploading file....done!");
		}
		else
		{
			if($debug) up_info("ub_band_mgmt.php: No file given");
		}
		$debug = 0;
		$logo_filename 		= $_FILES['logo']['name'] ;
	   	$logo_sourceFile	= $_FILES['logo']['tmp_name'];

		if($logo_filename != "")
		{
			//$subFolderName = "$name";
			$subFolderName = $category;
			
			if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
			if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $logo_filename, $AltFileName,  $logo_sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
			{
				$logo = $relativePath;
				if($debug) up_note("<b>The file '$long_filename' was uploaded to : , $relativePath</b>", 2);
				if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
				// echo "<img src= $actualURL><br>";
			}
			else
			{
				up_error("ub_band_mgmt.php - <b>Upload Failure</b> - $errorMessage");
				$image = "";
			}
			if ($debug) up_note( "Uploading file....done!");
		}
		else
		{
			if($debug) up_info("ub_band_mgmt.php: No file given");
		}
		$debug = 0;	
	}
	else if ($action == "update_arena_details" || $action == "update_hotel")
	{
		// $debug = 1;
	   	$image_filename 	= $_FILES['photo']['name'] ;
	   	$image_sourceFile 	= $_FILES['photo']['tmp_name'];

		if($image_filename != "")
		{
			//$subFolderName = "$name";
			$subFolderName = $category;
			
			if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
			if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $image_filename, $AltFileName,  $image_sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
			{
				$photo = $relativePath;
				if($debug) up_note("<b>The file '$image_filename' was uploaded to : , $relativePath</b>", 2);
				if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
				// echo "<img src= $actualURL><br>";
			}
			else
			{
				up_error("ub_band_mgmt.php - <b>Upload Failure</b> - $errorMessage");
				$image = "";
			}
			if ($debug) up_note( "Uploading file....done!");
		}
		else
		{
			if($debug) up_info("ub_band_mgmt.php: No file given");
		}
		$debug = 0;
	}	

	if($filename != "")
	{
		uly_get_lyrics_data($debug, "Lyrics", "new_lyrics", $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize, $NO_CONVERTION);
		$dateSuffix = date("Ymd_His");
		if (strpos($theKey, "#") > 0)
			$theKey = uly_get_low_key($theKey);
		if ($theKey != "")
			$AltFileName = $FileNamePrefix . $title . "-" . $artist .  "-key_" . $theKey . "_" . $dateSuffix ;
		else
			$AltFileName = $FileNamePrefix . $title . "-" . $artist . "_" . $dateSuffix ;
		// echo "<li> Alternate File Name: '$AltFileName' / Alt Folder Name: '$AltFolderName'";
		//$AltFileName = $name;
		$subFolderName = "$name";
		if ($debug) up_note( "Uploading file....$AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath");
		if( uf_upload_file(__FILE__, $AltFolderName , $subFolderName, $filename, $AltFileName,  $sourceFile, &$actualFileName, &$actualPath, &$actualURL, &$errorMessage, &$relativePath))
		{
			$filename = $relativePath;
			if($debug) up_note("<b>The file '$filename' was uploaded to : , $relativePath</b>", 2);
			if($debug) up_note("<b>Accessible via URL: $actualURL</b>", 2);
			// echo "<img src= $actualURL><br>";
		}
		else
		{
			up_error("test_video_util.php - <b>Upload Failure</b> - $errorMessage");
			$filename = "";
		}
		if ($debug) up_note( "Uploading file....done!");
	}	
	// == TWO STEPS;
	// = 1. Select Lyrics
	// = 2. Add video/MP3/midi/Spotify for that lyric
	 if ($action == "" || $action == "view_start_page")
	{
		// ubdc_print_page_title($pageTitle, $subTitle, $addURL, $addHoverText, $bandID, $PHP_FILE_NAME)
	//	ubdc_print_page_title("Startsida", "", "", "", $bandID, "SEARCH", $PHP_FILE_NAME);
		if ($PREVIOUS_ACTION == "")
			$PREVIOUS_ACTION = "$targetPHP" . "QM" . "actionEQUALSview_start_pageANDbandIDEQUALS" . $bandID;
	    ucssr_print_start_page($debug, $bandID, $bandMemberID, $PHP_FILE_NAME, $stateFlag, $PREVIOUS_ACTION);
		//ubdc_print_start_page($debug, $bandID, $bandMemberID, $PHP_FILE_NAME, "EDIT");    
	}
	// =====================================================
	// 	LYRICS
	// =====================================================
	else if ($action == "add_lyrics" || $action == "prepare_add_category" || $action == "add_category" || $action == "prepare_add_language" || $action == "add_language") //|| $action2 != "")
	{
	//	ubdc_print_page_title("Add song", "", "", "", $bandID);
	//	if ($action2 != "")	 up_note("action2 = $action2 ");
		switch ($action)
		{
		case "add_lyrics": 				$stateFlag = "ADD"; break;
		case "prepare_add_category": 	$stateFlag = "PREPARE_ADD_CATEGORY"; break;
		case "add_category": 		
			if (uly_add_category($debug, $dbase, $targetPHP, $category) == -1)
			{
				$stateFlag = "PREPARE_ADD_CATEGORY_INVALID";
				uly_print_add_lyrics_row($debug, $dbase, $targetPHP, $table, "INVALID_PARAMETERS", $title, $artist, $category);

			}
			else
				$stateFlag = "ADD"; 
			break; // _CATEGORY
		case "prepare_add_language": 	 $stateFlag = "PREPARE_ADD_LANGUAGE"; break;
		case "add_language": 	
			// $debug = 1;	
			if (uly_add_language($debug, $dbase, $targetPHP, $language) == -1)
			{
				$stateFlag = "PREPARE_ADD_LANGUAGE_INVALID";
				uly_print_add_lyrics_row($debug, $dbase, $targetPHP, $table, "INVALID_PARAMETERS", $title, $artist, $category, $language);

			}
			else
				$stateFlag = "ADD"; 
			break; // _CATEGORY
		}
		if($debug) echo "<li> calling uly_print_lyrics_table($debug, $dbase, vvv, $targetPHP, uID, $stateFlag, $edituID, $title, $artist, $name)";
	//	uly_print_lyrics_table($debug, $dbase, $tableName, $targetPHP, "uID", $stateFlag, $edituID, $title, $artist, $category, $language);
		$mode = "ADD";
		ucssr_print_header_title("Lägg till låt...");
		ucss_section_start($debug, "lyricListSection", "clearfix");
//		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $category, $language, $PREVIOUS_ACTION, $bChords);
		ulyr_print_add_lyric_form($debug, $bandID, $targetPHP, $tableName, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricListSection", "clearfix");
		//up_hr();		
		//uly_print_edit_lyric_form($debug, $dbase, $tableName, $songID, $targetPHP, $mode);
	}
	else if ($action2 == "do_add_lyrics")
	{
		// ADD IN THE MAIN DATABASE
		ubdc_print_page_title("Lägger till låt. ", "", "", "", $bandID);
		$db 	= "Lyrics";
		$table 	= "new_lyrics";
		if ($debug) up_note ("Adding lyrics in MAIN DB> $debug, $dbase, $table,$targetPHP, $table, title:<b>$title</b>, artist:$artist, $lyrics, $originalKey, cat:$category, lang:$language");
		// ADD in the Main Database...(if it EXIST the uID of it will be returned)
	
		if (($newSongID = uly_add_lyrics($debug, $MainDb, $targetPHP, $table, $title, $artist, $lyrics, $originalKey, $category, $language)) == -1)
		{
			// REDRAW The Input Fields again.
			// Probable NULL values, redraw the Add Form
			$mode = "ADD";
			$errorFlag = 1;
			ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $category, $language, $PREVIOUS_ACTION, $bChords);
			up_error("Unable to add the lyrics in the main database. Ignoring local (band) database.");
			return;
        }
        
		// And COPY (IMPORT) into the band Database...	
		uly_get_lyrics_data($debug, $MainDb, $table, $newSongID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language);

		// CONVERT TITLE and ARTIST
		if ($debug) up_info ("TITLE:$title - ARTIST:$artist");
	    // Force to add the lyrics with the SAME songID as from the Main DB
	
	    $cleanLyrics = str_replace("'", "''", $lyrics);
		$songID = uly_force_add_lyrics($debug, $dbase, $targetPHP, $tableName, $newSongID, $title, $artist, $cleanLyrics, $originalKey, $category, $language, $dateAdded, $bandID);
		if ($songID != $newSongID)
			up_error("Unable to copy the new song to the local database ($dbase.$tableName).");
		else  // If successful - Open it, to edit lyrics, BPM, key etc.
		{
			// Set the lyrics as Till Nasta rep per default
			uly_update_lyrics_status($debug, $dbase, "lyrics_status", $songID, 3, $bandID);
			$mode = "EDIT";
			// $debug =1;
			ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $category, $language, $PREVIOUS_ACTION, $bChords);
			$debug =0;
			up_hr();
			// And edit it.
			uly_print_edit_lyric_form($debug, $dbase, $tableName, $songID, $targetPHP, $mode, $bandID);		
			// Update the menu frame with the new lyrics
			ubdc_update_menu_frame();
		}
		// up_error("index.php: FIXME Copy the added song to the local database $dbase.$tableName");
	}
	else if ($action == "view_lyric")
	{
		if ($debug) echo "<li> prevAction:'$PREVIOUS_ACTION'";
		$debug = 0;
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		ucss_section_start($debug, "lyricTextSection", "clearfix");
		ucss_column_start($debug, "lyricTextColumn");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_column_end($debug, "lyricTextColumn", "clearfix");
		ucss_section_end($debug, "lyricTextSection");
	}	
	else if ($action == "edit_lyric")
	{
		if($debug) up_note("table=$tableName");
		$mode = "EDIT";
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION);
		ucss_section_end($debug);
		ucss_section_start($debug, "lyricTextSection", "clearfix");
		ulyr_print_edit_lyric_form($debug, $dbase, $tableName, $songID, $targetPHP, $mode, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug);
	}
	else if ($action == "update_lyric")
	{
		// $debug = 1;
		if ($singerID != "")
			uly_update_singer($debug, $songID, $singerID, $bandID); // NEW
		uly_update_lyrics($debug, $dbase, $tableName, $songID, $BPM, $theKey, $lyrics, $bandID, $fontSize);
		uly_update_lyrics_status($debug, $dbase, "lyrics_status", $songID, $lyric_status, $bandID);
		// VIEW LYRIC
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricHeaderSection");
//		up_hr();
		ucss_section_start($debug, "lyricTextSection", "clearfix");
		//$bResponsive = 1;
		//echo "<li> calling uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);";
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "view_all_lyrics" || $action == "")
	{
		$PREVIOUS_ACTION = ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME);
		if ($PREVIOUS_ACTION == "") up_error("ub_lyrics.php/view_all_lyrics: PREVIOUS_ACTION == NULL");
		ucssr_print_header_title("Alla låtar");
		ucss_section_start($debug, "lyricListSection", "clearfix");
//	 	uly_print_lyrics_table($debug, $dbase, $tableName, $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $artistFilter, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID, $PREVIOUS_ACTION );
	 	ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, "", $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricListSection");
	}
	else if ( 0 ) // $action == "view_rehearsed_songs" ) // DUPLICATE (see below)
	{
		$PREVIOUS_ACTION = ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME);
	 	//uly_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 0, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
	 	ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 0, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
	}
	else if ($action == "view_next_rehersal_songs" )
	{
//		ubdc_print_page_title("Låtar", "", "", "", $bandID);
		$PREVIOUS_ACTION = ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME);
		ucssr_print_header_title("Låtar - Nästa rep");
		ucss_section_start($debug, "lyricListSection", $class);
	 	ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 3, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricListSection", $class);
	}
	else if ($action == "view_proposed_songs" )
	{
//		ubdc_print_page_title("Låtar", "", "", "", $bandID);
		$PREVIOUS_ACTION = ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME);
		ucssr_print_header_title("Förslagna låtar");
		ucss_section_start($debug, "lyricListSection", $class);
	 	ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 1, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricListSection", $class);
	}
	else if ($action == "view_rehersed_songs" )
	{
//		ubdc_print_page_title("Låtar", "", "", "", $bandID);
		$PREVIOUS_ACTION = ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME);
		ucssr_print_header_title("Inrepade låtar");
		ucss_section_start($debug, "lyricListSection", $class);
	 	ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 0, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricListSection", $class);
	}
	else if($action == "filter_lyrics")
	{
//		ubdc_print_page_title("Låtar", "", "", "", $bandID);
		$PREVIOUS_ACTION = ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME);
	// 	uly_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 0, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
		ucssr_print_header_title("Reslutat sökning");
		ucss_section_start($debug, "lyricListSection", "clearfix");
//	 	uly_print_lyrics_table($debug, $dbase, $tableName, $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $artistFilter, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID, $PREVIOUS_ACTION );
	 	ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, "", $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "lyricListSection", $class);
		ucssr_print_header_title("Relaterade låtar att importera...");
		ucss_section_start($debug, "lyricListSection", "clearfix");
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $artistFilter, $instrumentFilter, $genreFilter, $bandID, 1 );
		ucss_section_end($debug, "lyricListSection", $class);
	}
	else if($action == "import_lyrics")
	{
//		ubdc_print_page_title("Importera låt", "", "", "", $bandID);
		ucssr_print_header_title("Importera låt...");
		ucss_section_start($debug, "lyricListSection", "clearfix");
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $artistFilter, $instrumentFilter, $genreFilter, $bandID, 1 );
		ucss_section_end($debug, "lyricListSection", $class);
	}
	else if($action == "filter_import_lyrics")
	{
//		ubdc_print_page_title("Filtrerade importlåtar", "", "", "", $bandID);
		ucssr_print_header_title("Importera låt (filter)...");
		ucss_section_start($debug, "lyricListSection", "clearfix");
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $artistFilter, $instrumentFilter, $genreFilter, $bandID, 1 );
		ucss_section_end($debug, "lyricListSection", $class);
//	 	uly_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 0, $titleFilter, $artistFilter, $instrument, $bandID);
	}
	else if($action == "do_import_lyric")
	{
		//$debug = 1;
//		ubdc_print_page_title("Importera låt", "", "", "", $bandID);
		uly_import_song_to_band($debug, $bandID, $songID, $targetPHP);
	 	// Förslag
	 	// echo "<li> Calling print table - proposed songs - Highlight: '$songID'";
	 	uly_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 1, $titleFilter, $artistFilter, $instrument, $bandID, $songID);
	 	//echo "<li> Calling print table - proposed songs...DONE!";
		// Update the menu frame with the new lyrics
		ubdc_update_menu_frame();

	//	uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $artistFilter, $instrumentFilter, $genreFilter, $bandID, 1 );
	}
	// =====================================================
	// 	COPY PRESENT DATA
	// =====================================================
	else if ($action == "copy_mp3")
	{
		$table = "mp3";
	 	uly_copy_old_data($debug, $dbase, $table, $songID, $targetPHP, $mp3_file);
		uly_view_lyric($debug, $dbase, $tableName, $songID, $stateFlag, $targetPHP);
		echo "<hr>\n";
	}
	// =====================================================
	// 	VIDEO
	// =====================================================
	else if ($action == "add_video" || $action == "add_videos")
	{
		$table = "videos";
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_add_form($debug, $MainDb, $table, $songID, $targetPHP, "PRE_ADD", $givenFileURLname, $givenDescription, $givenKey, $givenBPM, $givenInstrument, $givenEmbed, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_add_videos")
	{
		$table = "videos";
		// VIEW LYRICS TITLE
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);			
		ucss_section_end($debug, "lyricHeaderSection");
		// ADD INSTANCE		
		if (ulin_add_instance($debug, $MainDb, $table, $songID, $targetPHP, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded, $bandID, $PREVIOUS_ACTION) != -1)
		{
			// PRINT LIST if successful
			ucss_section_start($debug, "ulinPlayerSection");
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
			ucss_section_end($debug, "ulinPlayerSection");
		}
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}	
	else if ($action == "edit_videos")
	{
		$table = "videos";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		// $debug = 1;
		// PRINT VIDEO LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb, $table, $songID, "EDIT", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
	// PRINT LYRIC FOOTER
//		echo "<hr>\n";
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "update_videos")
	{
		$table = "videos";
		ulin_update_instance($debug, $MainDb, $table, $songID, $uID, $URL, $fileName, $description, $theKey, $BPM, $instrument, $embedded);
		if ($debug) up_note("Calling ulin_update_instance...Done",1);
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		// PRINT VIDEO LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb, $table, $songID, "VIEW", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT LYRIC FOOTER
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "view_videos")
	{
		$table = "videos";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		// $debug = 1;
		// PRINT VIDEO LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb, $table, $songID, "VIEW", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT LYRICS
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
		$debug = 0;
	}
	else if ($action == "delete_videos")
	{
		$table = "videos";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		ucss_section_start($debug, "lyricTextSection");
		ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_videos", $uID, $bgcolor, $bandID);
		ucss_section_end($debug, "lyricTextSection");
		// PRINT LYRIC FOOTER
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_delete_videos")
	{
		$table = "videos";
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		echo "<hr>\n";
		if (ulin_verify_password($debug, $MainDb, $password))
		{
			ulin_delete_instance($debug, $MainDb, $table, $songID, $uID);
			// PRINT VIDEO LIST
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		}
		else
		{
			up_error("Incorrect password.");
			up_warning("Three invalid attempts will block your IP-address from this site.");
			$bgcolor_red = "ffaaaa";
			ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_mp3", $uID, $bgcolor_red, $bandID);
		}
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
	}
	// =====================================================
	// 	SPOTIFY
	// =====================================================
	else if ($action == "view_spotify")
	{
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		// PRINT SPOTIFY LIST			
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb, "spotify", $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);	
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "add_spotify")  // 1. PRINT ADD FORM for Spotify
	{
		$table = "spotify";
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");
		// PRINT ADD SPOTIFY FORM			
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_add_form($debug, $MainDb, $table, $songID, $targetPHP, "PRE_ADD", $givenFileURLname, $givenDescription, $givenKey, $givenBPM, $givenInstrument, $givenEmbed, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_add_spotify") // 2. DO ADD Spotify URL
	{
		$table = "spotify";
		// VIEW LYRICS TITLE
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);			
		ucss_section_end($debug, "lyricHeaderSection");
		// ADD INSTANCE		
		if (ulin_add_instance($debug, $MainDb, $table, $songID, $targetPHP, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded, $bandID, $PREVIOUS_ACTION) != -1)
		{
			// PRINT LIST if successful
			ucss_section_start($debug, "ulinPlayerSection");
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
			ucss_section_end($debug, "ulinPlayerSection");
		}
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "edit_spotify")
	{
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);				
		// PRINT SPOTIFY LIST
		ulinr_print_list($debug, $MainDb, "spotify", $songID, "EDIT", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
	}
	else if ($action == "update_spotify")
	{
		//$debug = 1;
		if ($debug) up_note("Calling ulin_update_instance...",1);
		ulin_update_instance($debug, $MainDb, "spotify", $songID, $uID, $URL, $fileName, $description, $theKey, $BPM, $instrument, $embedded);
		if ($debug) up_note("Calling ulin_update_instance...Done",1);
		$debug =0 ;
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		// PRINT SPOTIFY LIST				
		ulinr_print_list($debug, $MainDb, "spotify", $songID, "", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
	}
	else if ($action == "delete_spotify")
	{
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ulin_print_password_form($debug, $MainDb, "spotify", $songID, $targetPHP, "do_delete_spotify", $uID, $bgcolor, $bandID);
		echo "<hr>\n";
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
	}
	else if ($action == "do_delete_spotify")
	{
		$table = "spotify";
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		echo "<hr>\n";
			
		if (ulin_verify_password($debug, $MainDb, $password))
		{
			ulin_delete_instance($debug, $MainDb, "spotify", $songID, $uID);
			// PRINT SPOTIFY LIST
			ulinr_print_list($debug, $MainDb, "spotify", $songID, "", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);				
		}
		else
		{
			up_error("Incorrect password.");
			up_warning("Three invalid attempts will block your IP-address from this site.");
			$bgcolor_red = "ffaaaa";
			ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_mp3", $uID, $bgcolor_red, $bandID);
		}
		$debug = 0;
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
	}
	// =====================================================
	// 	MP3
	// =====================================================
	else if ($action == "add_mp3")
	{
		$table = "mp3";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT ADD FORM
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_add_form($debug, $MainDb, $table, $songID, $targetPHP, "PRE_ADD", $givenFileURLname, $givenDescription, $givenKey, $givenBPM, $givenInstrument, $givenEmbed, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_add_mp3")
	{
		$table = "mp3";
		// VIEW LYRICS TITLE
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
				
		// ADD INSTANCE		
		if (ulin_add_instance($debug, $MainDb, $table, $songID, $targetPHP, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded, $bandID, $PREVIOUS_ACTION) != -1)
		{
			// PRINT MP3 LIST if successful
			ucss_section_start($debug, "ulinPlayerSection");
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
			ucss_section_end($debug, "ulinPlayerSection");
		}
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}	
	else if ($action == "edit_mp3")
	{
		$table = "mp3";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT PLAYBACK LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb, $table, $songID, "EDIT", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "update_mp3")
	{
		$table = "mp3";
	//	 $debug = 1;
		ulin_update_instance($debug, $MainDb, $table, $songID, $uID, "", $filename, $description, $theKey, $BPM, $instrument, $embedded);
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT PLAYBACK LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb,$table, $songID, "", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "delete_mp3")
	{
		$table = "mp3";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT PLAYBACK LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_$table", $uID, $bgcolor, $bandID);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_delete_mp3")
	{
		$table = "mp3";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT PLAYBACK LIST
		ucss_section_start($debug, "ulinPlayerSection");
		if (ulin_verify_password($debug, $MainDb, $password))
		{
			if (ulin_delete_instance($debug, $MainDb, "$table", $songID, $uID))
				up_note("Successful deletion of $songID/$uID in table '$table'.");
		}
		else
		{
			up_error("Incorrect password.");
			up_warning("Three invalid attempts will block your IP-address from this site.");
			$bgcolor_red = "ffaaaa";
			ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_$table", $uID, $bgcolor_red, $bandID);
		}
		// PRINT MP3 LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");								
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "view_mp3")
	{
		$table = "mp3";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT MP3 LIST
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT LYRICS
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	// =====================================================
	// 	PLAYBACK
	// =====================================================
	else if ($action == "add_playback")
	{
		$table = "playback";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT ADD FORM
		ucss_section_start($debug, "ulinPlayerSection");
		ulinr_print_add_form($debug, $MainDb, $table, $songID, $targetPHP, "PRE_ADD", $givenFileURLname, $givenDescription, $givenKey, $givenBPM, $givenInstrument, $givenEmbed, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_add_playback")
	{
		$table = "playback";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		// PRINT ADD FORM
		ucss_section_start($debug, "ulinPlayerSection");
		// ADD INSTANCE		
		if (ulin_add_instance($debug, $MainDb, $table, $songID, $targetPHP, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded, $bandID, $PREVIOUS_ACTION) != -1)
		{
			// PRINT LIST if successful
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		}
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}	
	else if ($action == "edit_playback")
	{
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT PLAYBACK LIST
		ulinr_print_list($debug, $MainDb, "playback", $songID, "EDIT", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "update_playback")
	{
		// $debug = 1;
		ulin_update_instance($debug, $MainDb, "playback", $songID, $uID, "", $filename, $description, $theKey, $BPM, $instrument, $embedded);
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT PLAYBACK LIST
		ulinr_print_list($debug, $MainDb, "playback", $songID, "", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "delete_playback")
	{
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		ulin_print_password_form($debug, $MainDb, "playback", $songID, $targetPHP, "do_delete_playback", $uID, $bgcolor, $bandID);				
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_delete_playback")
	{
		$table = "playback";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// $debug = 1;		
		if (ulin_verify_password($debug, $MainDb, $password))
		{
			ulin_delete_instance($debug, $MainDb, $table, $songID, $uID);
		}
		else
		{
			up_error("Incorrect password.");
			up_warning("Three invalid attempts will block your IP-address from this site.");
			$bgcolor_red = "ffaaaa";
			ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_mp3", $uID, $bgcolor_red, $bandID);
		}
		$debug = 0;
		// PRINT PLAYBACK LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "view_playback")
	{
		// echo "<li> PREV = $PREVIOUS_ACTION";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT PLAYBACK LIST
		ulinr_print_list($debug, $MainDb, "playback", $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	// =====================================================
	// 	MIDI
	// =====================================================
	else if ($action == "add_midi") // OK!!
	{
		$table = "midi";
		// VIEW HEADER
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT ADD FORM
		ulinr_print_add_form($debug, $MainDb, $table, $songID, $targetPHP, "PRE_ADD", $givenFileURLname, $givenDescription, $givenKey, $givenBPM, $givenInstrument, $givenEmbed, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_add_midi")  // OK!!
	{
		$table = "midi";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// ADD INSTANCE		
		if (ulin_add_instance($debug, $MainDb, $table, $songID, $targetPHP, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded, $bandID, $PREVIOUS_ACTION) != -1)
		{
			// PRINT LIST if successful
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		}
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}	
	else if ($action == "edit_midi")
	{
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT MIDI LIST
		ulinr_print_list($debug, $MainDb, "midi", $songID, "EDIT", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "update_midi")
	{
		$table = "midi";
		// $debug = 1;
		if ($debug) up_note("Calling ulin_update_instance...",1);
		ulin_update_instance($debug, $MainDb, $table, $songID, $uID, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded);
		if ($debug) up_note("Calling ulin_update_instance...Done",1);
		$debug =0 ;
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT MIDI LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, "", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "delete_midi")	// OK!
	{
		$table = "midi";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_$table", $uID, $bgcolor, $bandID);	
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_delete_midi")
	{
		$table = "midi";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// $debug = 1;		
		if (ulin_verify_password($debug, $MainDb, $password))
		{
			ulin_delete_instance($debug, $MainDb,  $table, $songID, $uID);
		}
		else
		{
			up_error("Incorrect password.");
			up_warning("Three invalid attempts will block your IP-address from this site.");
			$bgcolor_red = "ffaaaa";
			ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_$table", $uID, $bgcolor_red, $bandID);
		}
		// PRINT MIDI LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "view_midi")
	{
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT MIDI LIST
		ulinr_print_list($debug, $MainDb, "midi", $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	// =====================================================
	// 	MUSIC SHEET
	// =====================================================
	else if ($action == "add_music_sheet")
	{
		$table = "music_sheet";		
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT ADD FORM
		ulinr_print_add_form($debug, $MainDb, $table, $songID, $targetPHP, "PRE_ADD", $givenFileURLname, $givenDescription, $givenKey, $givenBPM, $givenInstrument, $givenEmbed, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_add_music_sheet")
	{
		$table = "music_sheet";		
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// ADD INSTANCE		
		if (ulin_add_instance($debug, $MainDb, $table, $songID, $targetPHP, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded, $bandID, $PREVIOUS_ACTION) != -1)
		{
			// PRINT LIST
			ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		}
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "edit_music_sheet")
	{
		$table = "music_sheet";		
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT MUSIC SHEET LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, "EDIT", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "update_music_sheet")
	{
		$table = "music_sheet";		
		ulin_update_instance($debug, $MainDb, $table, $songID, $uID, $URL, $filename, $description, $theKey, $BPM, $instrument, $embedded);
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT MUSIC SHEET LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, "", $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "delete_music_sheet")
	{
		$table = "music_sheet";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_$table", $uID, $bgcolor, $bandID);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "do_delete_music_sheet")
	{
		$table = "music_sheet";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		if (ulin_verify_password($debug, $MainDb, $password))
		{
			ulin_delete_instance($debug, $MainDb,  $table, $songID, $uID);
		}
		else
		{
			up_error("Incorrect password.");
			up_warning("Three invalid attempts will block your IP-address from this site.");
			$bgcolor_red = "ffaaaa";
			ulin_print_password_form($debug, $MainDb, $table, $songID, $targetPHP, "do_delete_$table", $uID, $bgcolor_red, $bandID);
		}
		// PRINT MUSIC SHEET LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}
	else if ($action == "view_music_sheet")
	{
		$table = "music_sheet";
		// PRINT LYRIC HEADER
		ucss_section_start($debug, "lyricHeaderSection", "clearfix");
		ulyr_view_lyric_head($debug, $dbase, $tableName, $songID, $targetPHP, $theKey, $fontSize, $mode, $bandID, $errorFlag, $title, $artist, $selectedCategory, $selectedLanguage, $PREVIOUS_ACTION, $bChords);
		ucss_section_end($debug, "lyricHeaderSection");				
		ucss_section_start($debug, "ulinPlayerSection");
		// PRINT MUSIC SHEET LIST
		ulinr_print_list($debug, $MainDb, $table, $songID, $stateFlag, $uID, $targetPHP, $bandID, $PREVIOUS_ACTION);
		ucss_section_end($debug, "ulinPlayerSection");
		// PRINT TEXT (Lyrics) SECTION
		ucss_section_start($debug, "lyricTextSection");				
		// PRINT LYRIC FOOTER
		uly_view_lyric_body($debug, $dbase, $tableName, $songID, $theKey, $fontSize, $bandID, $bChords, $bResponsive);
		ucss_section_end($debug, "lyricTextSection");
	}

	// ========================================
	// ========================================
	//    BAND MANAGEMENT SECTION
	// ======================================== 
	// ========================================
	
// 	if($debug) echo "<h1>$bandName</h1><hr>";

	// ========================================
	//   SEARCH
	// ======================================== 
	else if ($action == "search")
	{
		// ubdc_print_page_title("Sökning", "", "", "", $bandID, "SEARCH", $PHP_FILE_NAME);
		ucss_section_start($debug, "section3", $class);
		ubdc_search_responsive ($debug, $bandID, $PHP_FILE_NAME, $search_value);
		ucss_section_end($debug, "section3", $class);
	}
	// ========================================
	//   REPERTOIR MANAGEMENT ubd_rp_
	// ======================================== The functions reside in util_band_repertoir.php ===
	else if ($action == "view_old_repertoirs")  // ALL
	{
//  ubdc_print_page_title($pageTitle, $subTitle, $addURL, $addHoverText, $bandID)
			ubdc_print_page_title("Gamla Låtlistor", "", "", "", $bandID);
		//	$debug = 1;
			if ($debug) echo "<li> VIEW ALL REPERTOIRS - debug=$debug";
	    	ubd_rp_view_old_repertoirs($debug,  $bandID, "", $PHP_FILE_NAME, $LYRICS_PHP);	  // VIEW ALL  
			if ($debug) echo "<li> VIEW ALL REPERTOIRS...DONE";
	}
	else if ($action == "view_repertoirs")  // ALL
	{
		//	$debug = 1;
		if ($debug) echo "<li> VIEW ALL REPERTOIRS - debug=$debug";
		ucssr_print_header_title("LÅTLISTOR");
		ucss_section_start($debug, "section3", $class);
	    ubd_rp_view($debug,  $bandID, "", $PHP_FILE_NAME, $LYRICS_PHP);	  // VIEW ALL  
	    ub_nice_link("$targetPHP?action=view_old_repertoirs&bandID=$bandID", "Gamla låtlistor");
		ucss_section_end();
		if ($debug) echo "<li> VIEW ALL REPERTOIRS...DONE";
	}
	else if ($action == "view_repertoir")  // SINGLE
	{
		$rp_name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
		$rp_name = strtoupper($rp_name);
		$LastUpdate = ubd_rp_get_last_update($debug, $bandID, $rID);
		ucss_section_start($debug, "section3", $class);
		// $debug, $pageTitle, $backAction, $addAction, $titleImage, $editAction, $valign, $iconHeight, 1, $targetPHP
		if ($layout == "" || $layout == "allSetsOnOnePage")
			ucssr_print_repertoir_title($debug, "$rp_name", "", "$PHP_FILE_NAME?action=view_repertoir&bandID=$bandID&rID=$rID&layout=wholeRepertoir", "", $bandID, "EXPAND");
		else
			ucssr_print_repertoir_title($debug, "$rp_name", "", "$PHP_FILE_NAME?action=view_repertoir&bandID=$bandID&rID=$rID&layout=allSetsOnOnePage", "", $bandID, "COLLAPSE");
		ucss_section_end();
		//	$debug = 0;
		if ($debug) echo "<li> VIEW SINGLE REPERTOIR - debug=$debug";
		
		ucss_section_start($debug, "section3", $class);
		$bResponsive = 1;
	    ubd_rp_view($debug,  $bandID,  $rID, $PHP_FILE_NAME, $LYRICS_PHP, $layout, $PREVIOUS_ACTION, $bResponsive);	  // VIEW SPECIFIC  
		ucss_section_end();
		if ($debug) echo "<li> VIEW SINGLE REPERTOIR...DONE";
	}
	else if ($action == "print_add_repertoir_form")
	{
			ubdc_print_page_title("Låtlistor", "", "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
			//$debug = 1;
	    	ubd_rp_print_form($debug, $bandID, $edit, $rID, "", "", "",  $PHP_FILE_NAME);
			$debug = 0;
	}
	else if ($action == "add_repertoir")
	{	
			ubdc_print_page_title("Låtlistor", "", "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
			$debug = 1;
	       	ubd_rp_add($debug, $bandID, $name, $nofSets, $PHP_FILE_NAME ); 
			$debug = 0;
	    	ubd_rp_view($debug,  $bandID,  "", $PHP_FILE_NAME);	  // VIEW ALL  
	}
	else if ($action == "add_repertoir_pdf")
	{	
			$rp_name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
			ubdc_print_page_title("$rp_name", "", "", "", $bandID);
		//	$debug = 1;
	       	ubd_rp_print_add_pdf_form($debug, $bandID, $rID, $targetPHP ); 
			$debug = 0;
	    	//ubd_rp_view($debug,  $bandID,  "", $PHP_FILE_NAME);	  // VIEW ALL  
	}
	else if ($action == "upload_repertoir_pdf")
	{	
			ubdc_print_page_title("Låtlistor", "", "", "", $bandID);
		//	$debug = 1;
		//	if  ($long_document == "" && )
	   //    		ubd_rp_print_add_pdf_form($debug, $bandID, $rID, $targetPHP ); 
	    //   	else
	       	ubd_rp_update_pdf($debug, $bandID, $rID, $long_document, $short_document);
			$debug = 0;
	    	ubd_rp_view($debug,  $bandID, "", $targetPHP, $LYRICS_PHP);	  // VIEW ALL  
	}
	else if ($action == "do_add_repertoir")
	{	
			ubdc_print_page_title("Låtlistor", "", "", "", $bandID);
		//	$debug = 1;
	       	ubd_rp_add_pdf($debug, $bandID, $name, $nofSets, $PHP_FILE_NAME ); 
			$debug = 0;
	    	ubd_rp_view($debug,  $bandID,  "", $PHP_FILE_NAME);	  // VIEW ALL  
	}
	else if ($action == "edit_repertoir")
	{	
		$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
		// ubdc_print_page_title("$name", "", "", "", $bandID); // $PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID
		ucssr_print_header_title("Redigera låtlista - $name");
		ucss_section_start($debug, "section3", $class);	
       	ubd_rp_print_edit_title_form($debug, $bandID, $rID, $targetPHP ); 
	    // up_hr();
	    ubd_rp_print_add_pdf_form($debug, $bandID, $rID, $targetPHP ); 
	    // up_hr();
			// $debug=1;
		if ($debug) echo "<li> EDIT REPERTOIR - debug=$debug";
	    ubd_rp_print_form($debug, $bandID, 1, $rID, "", "", "",  $PHP_FILE_NAME);
		if ($debug) echo "<li> EDIT REPERTOIR - ...DONE";
		ucss_section_end($debug, "section3", $class);	
	}
	else if ($action == "add_song_to_set")
	{	
			$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
			ubdc_print_page_title("Låtlistor", $name, "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
			//$debug=1;
			if($debug) up_info("Adding song: $songID to set $sID (in repertoir:$rID");
			ubd_rp_add_repertoir_song($debug, $bandID, $rID, $sID, $songID, $comment);
			$debug=0;
			if ($debug) echo "<li> ADD SONG REPERTOIR - debug=$debug";
			// $debug=1;
			//  ubd_rp_print_form($debug, $bandID, $edit, $rehersalID, $name,  $nofSets, $errorIndication)
			if ($debug) up_warning ("Printing edit table");
	       	ubd_rp_print_form($debug, $bandID, 1, $rID, "", "", "",  $PHP_FILE_NAME);
			if ($debug) up_warning ("Printing edit table...DONE!");
	       // 	ubd_rp_print_form($debug, $bandID, $edit, $rehersalID, $name,  $nofSets, $errorIndication, $bandMgmtPHP);
			if ($debug) echo "<li> DD SONG REPERTOIR - ...DONE";
			ubd_rp_update_last_update($debug, $rID);
	}
	else if ($action == "delete_song_from_set")
	{
			$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
			ubdc_print_page_title("Låtlistor", $name, "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
			if ($debug) up_note("removing song: calling ubd_rp_remove_repertoir_song($debug, $rID, $sID, $iThSong);");
			ubd_rp_remove_repertoir_song($debug, $bandID, $rID, $sID, $iThSong);
			if ($debug) up_note("removing song: calling ubd_rp_remove_repertoir_song($debug, $rID, $sID, $iThSong);...DONE!");
	       	ubd_rp_print_form($debug, $bandID, 1, $rID, "", "", "",  $PHP_FILE_NAME);
	
	}
	else if ($action == "add_manuscript")
	{	
			$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
			ubdc_print_page_title("Låtlistor", $name, "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
			// $debug=1;
			if($debug) up_info("Adding manuscript: $songID to set $sID (in repertoir:$rID");
			// ubd_rp_add_manuscript($debug, $bandID, $iSet, $iThSong, $rID)
			ubd_rp_add_manuscript($debug, $bandID,  $iSet, $iThSong, $rID);
			$debug=0;
			if ($debug) echo "<li> ADD SONG REPERTOIR - debug=$debug";
			// $debug=1;
			//  ubd_rp_print_form($debug, $bandID, $edit, $rehersalID, $name,  $nofSets, $errorIndication)
			if ($debug) up_warning ("Printing edit table");
	       	ubd_rp_print_form($debug, $bandID, 1, $rID, "", "", "",  $PHP_FILE_NAME);
			if ($debug) up_warning ("Printing edit table...DONE!");
	       // 	ubd_rp_print_form($debug, $bandID, $edit, $rehersalID, $name,  $nofSets, $errorIndication, $bandMgmtPHP);
			if ($debug) echo "<li> DD SONG REPERTOIR - ...DONE";
	}
	else if ($action == "delete_manuscript")
	{	
			$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
			ubdc_print_page_title("Låtlistor", $name, "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
			//$debug=1;
			if($debug) up_info("Deleting manuscript: $songID to set $sID (in repertoir:$rID");
			// ubd_rp_add_manuscript($debug, $bandID, $iSet, $iThSong, $rID)
			ubd_rp_delete_manuscript($debug, $bandID,  $uID);
			$debug=0;
	       	ubd_rp_print_form($debug, $bandID, 1, $rID, "", "", "",  $PHP_FILE_NAME);
	}
	else if ($action == "print_copy_repertoir_form")
	{	
		//ubdc_print_page_title("Kopiera låtlista", "", "", "", $bandID);
		ucssr_print_header_title("Kopiera låtlista");
		ucss_section_start($debug, "section3", $class);	
		ubd_rp_print_copy_form($debug, $bandID, $rID, $targetPHP);
		ucss_section_end($debug, "section3", $class);	
	}	
	else if ($action == "copy_repertoir")
	{	
		$rp_name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
		ubdc_print_page_title("$rp_name", "", "", "", $bandID);
	
// BEFORE		ubdc_print_page_title("Kopierad låtlista - $repertoir_name", "", "", "", $bandID);
		$newRID = ubd_rp_copy_repertoir($debug, $bandID, $rID, $targetPHP, $repertoir_name);
//		ubd_rp_print_nice_repertoir($debug, $bandID, $NewrID, $layout, $chordLayout, $userID, $HideIcons, $bandMgmtPHP, $lyricsPHP);
	    ubd_rp_view($debug, $bandID, $newRID, $PHP_FILE_NAME, $LYRICS_PHP, $layout);	  // VIEW SPECIFIC  
	}	
	else if ($action == "place_song_in_repertoir")
	{	
			$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
			ubdc_print_page_title("Låtlistor", $name, "", "", $bandID);  // $PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID
		//	$debug=1;
			if ($debug) echo "<li> EDIT REPERTOIR - EDIT PLACE FOR";
			ubd_rp_update_repertoir_song($debug, $bandID, $rID, $sID, $iThSong, $songID, $subaction, $newPosition);
			$debug=0;
			//
	       	ubd_rp_print_form($debug, $bandID, 1, $rID, $sID, $iThSong, $param,  $PHP_FILE_NAME);
			if ($debug) echo "<li> EDIT REPERTOIR - ...DONE";
	}
	else if ($action == "print_edit_repertoir_form")
	{
	    	ubd_rp_print_form($debug, $bandID, 1, $repertoirID, "", "", "",  $PHP_FILE_NAME);
	}
	else if ($action == "update_repertoir")
	{
		// $debug = 1;
		ubdc_print_page_title("Låtlistor", "", "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
	    ubd_rp_update( $debug, $bandID, $repertoirID, $name );	
	//	ubd_rp_update_last_update($debug, $repertoirID);
	    ubd_rp_view($debug,  $bandID, "", $PHP_FILE_NAME, $LYRICS_PHP);	  // VIEW ALL  
//	    ubd_rp_view($debug,  $bandID,  $repertoirID, $PHP_FILE_NAME, $LYRICS_PHP, $layout, $PREVIOUS_ACTION);	  // VIEW SPECIFIC  
	}
	else if ($action == "delete_repertoir")  // SAFE QUESTION
	{
		ucssr_print_header_title("Ta bort låtlista");
		ucss_section_start($debug, "section3", $class);	
	    ubd_rp_print_delete_question($debug, $bandID, $rID, $PHP_FILE_NAME);	
		ucss_section_end($debug, "section3", $class);	
	}
	else if ($action == "do_delete_repertoir")
	{
		// 	$debug=1;
			if ($debug) echo "<li> DELETE REPERTOIR - debug=$debug";
	    	ubd_rp_delete($debug, $bandID, $rID);	
			if ($debug) echo "<li> DELETE REPERTOIR - DONE!";
			ubdc_print_page_title("Låtlistor", "", "$PHP_FILE_NAME?action=print_add_repertoir_form&bandID=$bandID", "", $bandID);
	    	ubd_rp_view($debug,  $bandID, "", $PHP_FILE_NAME, $LYRICS_PHP);	  // VIEW ALL  
	}	
	// ========================================
	//   REHERSAL MANAGEMENT ubd_rh_
	// ======================================== The functions reside in util_band_rehersal.php ===
	else if ($action == "view_rehersals")
	{
//		ubdc_print_page_title("Rep", "Kommande rep", "$PHP_FILE_NAME?action=print_add_rehersal_form&bandID=$bandID", "Add rehersal", $bandID);
		$debug = 0;
		if ($debug) up_note ("VIEW REHERSALS");
		ucssr_print_header_title("REP");
		ucss_section_start($debug, "section3", $class);	
	 	ubd_rh_view($debug, $bandID,  $rehersalID, $PHP_FILE_NAME, $LYRICS_PHP, $PREVIOUS_ACTION);	
		ucss_section_end($debug, "section3", $class);	
		ucssr_print_header_title("TILLGÄNGLIGHETSSCHEMA");
		ucss_section_start($debug, "section3", $class);	
		ubd_rh_print_availability_matrix($debug, $targetPHP, $bandID, $userID, 30, $goto);
		ucss_section_end($debug);
//	 	ub_nice_link("$targetPHP?action=print_rehersal_availability&bandID=$bandID", "Tillgänglighetsschema", $target, "Fyll i när du kan repa!", $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor);
    
		if ($debug) up_note ("VIEW REHERSALS...Done");
	}
	else if ($action == "print_add_rehersal_form")
	{
	// $pageTitle, $subTitle, $addURL, $addHoverText, $bandID)
//			ubdc_print_page_title("Rep", "Lägg till rep", $addURL, $addHoverText, $bandID);
		//$debug = 1;
		if ($debug) up_note ("Add REHERSAL");
//function ubd_rh_print_form($debug, $bandID, $edit, $rehersalID)

	    ubd_rh_print_form($debug, $bandID, 0, $rehersalID, $date, $time, $notes, $location, $PHP_FILE_NAME);
	}
	else if ($action == "add_rehersal")
	{
//		ubdc_print_page_title("Rep" , "", "$PHP_FILE_NAME?action=print_add_rehersal_form&bandID=$bandID", "Add rehersal", $bandID);
		if ($date < $today)
		{
	    	ubd_rh_print_form($debug, $bandID, 0, $rehersalID, $date, $time, $notes, $location, $PHP_FILE_NAME);	
			return;
		}
		//$debug = 1;
		if ($debug) up_note ("Add REHERSAL DATE");
	    if (ubd_rh_add($debug, $bandID, $date, $time, $note, $location  ) <=0)
	    {
			$debug = 0;
	    	// ubd_rh_print_form($debug, $bandID, 0, $date, $time, $notes, $location);
	    }
	    else 
	    {
			$debug = 0;
			ubd_rh_view($debug, $bandID,  $rehersalID, $PHP_FILE_NAME, $LYRICS_PHP);
			// Update the menu frame with the new lyrics
		}
		$debug = 0;
		if ($debug) up_note ("Updating menu frame");
		ubdc_update_menu_frame();
	}
	else if ($action == "edit_rehersal")
	{
		// FIX ME - PRINT date
//		ubdc_print_page_title("Redigera rep", "");
	    ubd_rh_print_form($debug, $bandID, 1, $uID, $date, $time, $notes, $location, $PHP_FILE_NAME);
		if ($debug) up_note ("Updating menu frame");
	}
	else if ($action == "update_rehersal")
	{
		// TODO: Fix parameters  - according to the Database table
//		ubdc_print_page_title("Rep", "Kommande rep", "$PHP_FILE_NAME?action=print_add_rehersal_form&bandID=$bandID", "Add rehersal", $bandID);
		// $debug = 1;
	   ubd_rh_update( $debug, $bandID, $uID, $date, $time, $note, $location );	
		$debug = 0;
		ubd_rh_view($debug, $bandID,  $uID, $PHP_FILE_NAME, $LYRICS_PHP);
	}
	else if ($action == "delete_rehersal")
	{
		//$debug=1;
		if ($debug) echo "<li> DELETE REHERSAL ";
//		ubdc_print_page_title("Rep", "Kommande rep", "$PHP_FILE_NAME?action=print_add_rehersal_form&bandID=$bandID", "Add rehersal", $bandID);
	    ubd_rh_delete($debug, $bandID, $uID);	
		ubd_rh_view($debug, $bandID, "" , $PHP_FILE_NAME, $LYRICS_PHP);
		ubdc_update_menu_frame();
	}
	else if ($action == "do_delete_rehersal")
	{
		// IS THIS EVER USED? No - dont think so.
			$debug=1;
			if ($debug) echo "<li> DELETE REHERSAL ";
	    	ubd_rh_delete($debug, $bandID, $rID);	
			if ($debug) echo "<li> DELETE REHERSAL - DONE!";
			ubdc_update_menu_frame();
	}	
	// ======================================
	// TILLGÄNGLIGHET / AVAILABILITY
	else if ($action == "print_rehersal_availability")
	{
		// ubdc_print_page_title("Tillgänglighetsschema", "", "", "Lägg till", $bandID);
		$debug = 0;
		if ($debug) up_note ("VIEW REHERSALS");
	 	// ubd_rh_view($debug, $bandID,  $rehersalID, $PHP_FILE_NAME, $LYRICS_PHP);	
	    ucssr_print_header_title("TILLGÄNGLIGHETSSCHEMA");
		ucss_section_start($debug, "section3", $class);	
		ubd_rh_print_availability_matrix($debug, $targetPHP, $bandID, $userID, $nofDays, $goto);
		ucss_section_end($debug);
		if ($debug) up_note ("VIEW REHERSALS...Done");
	
	}
	else if ($action == "edit_rehersal_availability")
	{
		// ubdc_print_page_title("Tillgänglighetsschema", "", "", "Lägg till", $bandID);
		$debug = 0;
		if ($debug) up_note ("EDIT AVAILABILITY");
	 	// ubd_rh_view($debug, $bandID,  $rehersalID, $PHP_FILE_NAME, $LYRICS_PHP);	
		ucss_section_start($debug, "section3", $class);
		ubd_rh_print_availability_matrix($debug, $targetPHP, $bandID, $userID, $nofDays, $goto);
		ucss_section_end($debug);
		if ($debug) up_note ("EDIT AVAILABILITY...Done");
	
	}
	else if ($action == "add_availability")
	{
		// ubdc_print_page_title("Tillgänglighetsschema", "", "", "Lägg till", $bandID);
		$debug = 0;
		if ($debug) up_note ("ADD AVAILABILITY");
	 	// ubd_rh_view($debug, $bandID,  $rehersalID, $PHP_FILE_NAME, $LYRICS_PHP);	
	 	ubd_rh_add_availability($debug, $bandID, $userID, $year, $month, $day, $status);
		ucss_section_start($debug, "section3", $class);	
		ubd_rh_print_availability_matrix($debug, $targetPHP, $bandID, $userID, $nofDays, $goto);
		ucss_section_end($debug, "section3");
		if ($debug) up_note ("ADD AVAILABILITY...Done");
	}
	// ========================================
	//   LYRICS MANAGEMENT ubd_ly_
	// ======================================== The functions reside in util_band_lyrics.php ===
	// Managed in index.php

	// ========================================
	//   GIG MANAGEMENT ubd_gi_
	// ======================================== The functions reside in util_band_gig.php ===
	else if ($action == "print_add_gig_form")
	{
		ubdc_print_page_title("Lägg till spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
	    ubd_gi_print_form($debug, $bandID, 0, "", $date, $time, $notes, $arenaID, $PHP_FILE_NAME, "ADD");
	}
	else if ($action == "prepare_add_arena")
	{
		ubdc_print_page_title("Lägg till spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
	    ubd_gi_print_form($debug, $bandID, 0, "", $date, $time, $notes, $arenaID, $PHP_FILE_NAME, "PREPARE_ADD");
	}
	else if ($action == "add_arena")
	{
		ubdc_print_page_title("Lägg till arena", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		// $debug = 1;
		$arenaID = ubdc_add_arena($debug, $name, $streetAddress, $city, $URL);
		if ($gigID > 0) 
		{
			$edit = 1;
			$stateFlag = "EDIT";
	    	ubd_gi_update( $debug, $bandID, $gigID, $date, $time, $eventType, $arenaID );	
		}
		else
		{
			$edit = 0;
			$stateFlag = "ADD";
		}
	    ubd_gi_print_form($debug, $bandID, $edit, $gigID, $date, $time, $notes, $arenaID, $PHP_FILE_NAME, $stateFlag);
	    // ubd_gi_view($debug, $bandID,  $gigID, $PHP_FILE_NAME);	   
	}
/* NOT USED */
	else if ($action == "edit_arena")
	{
		ubdc_print_page_title("Redigera arena", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		// $debug = 1;
		ubdc_print_new_arena_item_form($targetPHP, $errorFlag, $arenaID, $bandID, $gigID);
	}
	else if ($action == "update_arena")
	{
		ubdc_print_page_title("Uppdatera arena", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		// $debug = 1;
		//ubdc_print_new_arena_item_form($targetPHP, $errorFlag, $selectedArena, $bandID, $gigID);
	}
	// GIG
	else if ($action == "add_gig")
	{	
//		ubdc_print_page_title("Lägger till spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		// $debug = 1;
	    if (ubd_gi_add($debug, $bandID, $date, $time, $eventType, $arenaID, $PHP_FILE_NAME, $repertoirID ) <=0)
	    {
			$debug = 0;
//	    	ubd_gi_print_form($debug, $bandID, 0, "", $date, $time, $notes, $arenaID, $PHP_FILE_NAME, "ERROR");
	    	ubd_gi_print_form($debug, $bandID, 0, "", $date, $time, $notes, $arenaID, $PHP_FILE_NAME, "INVALID_PARAMETERS");
	    }
	    else 
	    {
			$debug = 0;
	    	ubd_gi_view($debug, $bandID,  "", $PHP_FILE_NAME);	   
		} 
		ubdc_update_menu_frame();
	}
	else if ($action == "view_gigs")
	{
//		ubdc_print_page_title("Spelningar", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		// $debug = 1;
		ucssr_print_header_title("SPELNINGAR");
		ucss_section_start($debug, "section3", $class);
	    ubd_gi_view($debug, $bandID,  "", $PHP_FILE_NAME);	    
		ucss_section_end($debug);
	}	
	else if ($action == "view_gig")
	{
		if ($gigID > 0)
		{
			ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
//			ubdc_print_page_title($eventType, "", "", "Lägg till spelning...", $bandID);			
			// ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
			ucssr_print_header_title("SPELNING - $eventType");
			ucss_section_start($debug, "section3", $class);
	    	ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, $date);	   
			ucss_section_end($debug, "section3", $class);
	    } 
	    else // View by date
	    {
	    	$gigID = ubd_gi_get_gig_id($debug, $bandID, $date);
			ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
//			ubdc_print_page_title($eventType, "", "", "Lägg till spelning...", $bandID);			
//			ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
// NOT INVALID - up_error("gigID invalid NULL value.");
	   		// ubd_gi_view($debug, $bandID,  "", $PHP_FILE_NAME, $date);	    			    
	    	ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, $stateFlag, $travelID, $date);	   
		}
	}
	else if ($action == "edit_gig")
	{
		if ($gigID != "" && $gigID[0] == "?")
			$gigID = "";
		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
/*
		if ($eventType != "")
			ubdc_print_page_title($eventType, "", "", "", $bandID);	
		else		
			ubdc_print_page_title("Lägg till spelning", "", "", "", $bandID);
*/
		//$debug = 1;
	    ubd_gi_print_form($debug, $bandID, 1, $gigID, $date, $time, $notes, $arenaID, $PHP_FILE_NAME, "EDIT");
	}
	else if ($action == "update_gig")
	{
		ubd_gi_get_data($debug, $bandID,  $gigID, &$$old_date, &$$old_time, &$old_eventType, &$old_arenaID, &$old_repertoirID);
		if ($eventType == "")
			$eventType = $old_eventType;
//		ubdc_print_page_title($eventType, "", "", "Lägg till spelning...", $bandID);			
	    if (ubd_gi_update( $debug, $bandID, $gigID, $date, $time, $eventType, $arenaID, $repertoirID, $PHP_FILE_NAME ) < 0)
	    {
	    	ubd_gi_print_form($debug, $bandID, 0, "", $date, $time, $notes, $arenaID, $PHP_FILE_NAME, "INVALID_PARAMETERS");
	    
	    }	
		else	// Updated successfully
		{
	    	ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, $stateFlag, $travelID, $date);	 
	    }  
	}
	else if ($action == "delete_gig")
	{
//		ubdc_print_page_title("Spelningar", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		// $debug = 1;
	    	ubd_gi_delete($debug, $bandID, $gigID);	
		$debug = 0;
	    	ubd_gi_view($debug, $bandID,  "", $PHP_FILE_NAME);	   
		ubdc_update_menu_frame();
	}
	// ====== GIG DETAILS 
	else if ($action == "edit_gig_details")
	{
			ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
//			ubdc_print_page_title($eventType, "", "", "", $bandID);			
		//$debug = 1;
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "EDIT_GIG_DETAILS", $travelID, $date);	   
	}
	else if ($action == "update_gig_details")
	{
		//ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
			ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
//			ubdc_print_page_title($eventType, "", "", "", $bandID);			
	//	$debug = 1;
	    ubd_gi_update_gig_details( $debug, $bandID, $gigID, $accessTime, $gatheringTime, $departureTime, $unpackTime, $soundCheckTime, $soundCheckEndTime, $onStageTime, $gatheringDate, $departureDate, $unpackDate, $soundCheckDate );	
		$debug = 0;
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, $stateFlag, $travelID, $date);	   
	}
	// ======================================== ARENA DETAILS
	else if ($action == "edit_arena_details")
	{
		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
		ucssr_print_header_title("Redigera spelning");
//			ubdc_print_page_title($eventType, "", "", "", $bandID);			
//		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		//$debug = 1;
		ucss_section_start($debug, "section3", $class);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "EDIT_ARENA_DETAILS", $travelID, $date);	   
		ucss_section_end($debug);
	}
	else if ($action == "update_arena_details")
	{
		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
	//ubdc_print_page_title($eventType, "", "", "", $bandID);			
	//	$debug = 1;
		$arenaID = ubdc_gi_update_arena( $debug, $arenaID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL, $photo );
		if ($arenaID > 0)
			ubd_gi_update($debug, $bandID, $gigID, "", "", "", $arenaID, "", $PHP_FILE_NAME);
		ucssr_print_header_title("SPELNING - $eventType");
		ucss_section_start($debug, "section3", $class);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, $stateFlag, $travelID, $date);	   
		ucss_section_end($debug);
	}	
	// ======================================== REPERTOIR DETAILS
	else if ($action == "edit_repertoir_details")
	{
		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
		ubdc_print_page_title($eventType, "", "", "", $bandID);			
		//$debug = 1;
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "EDIT_REPERTOIR_DETAILS", $travelID, $date);	   
	}
	else if ($action == "update_arena_details")
	{
			ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
			ubdc_print_page_title($eventType, "", "", "", $bandID);			
	//	$debug = 1;
		$arenaID = ubdc_gi_update_arena( $debug, $arenaID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL );
		if ($arenaID > 0)
			ubd_gi_update($debug, $bandID, $gigID, "", "", "", $arenaID, "", $PHP_FILE_NAME);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, $stateFlag, $travelID, $date);	   
	}		
	// ======================================== ACCOMODATION
	else if ($action == "print_add_hotel_form")
	{
		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "ADD_ACCOMODATION");	   
		ubdc_update_menu_frame();
	}
	else if ($action == "add_hotel")
	{
		if ($name == "" ||  $streetAddress== "" ||  $city== "" ||   $phoneNumber== "" ||   $URL== "" )
		{
			up_error ("Invalid NULL VALUES . $name == '' ||  $streetAddress== '' ||  $city== '' ||   $phoneNumber== '' ||   $URL");
		}
		else
		{
			$hotelID = ubd_gi_add_hotel($debug, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL);
			ubd_gi_add_gig_accommodation($debug, $gigID, $hotelID);
	    	ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "");
	    }	   
	}
	else if ($action == "edit_hotel")
	{
 		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
	   	ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "EDIT_HOTEL");	   
	}
	else if ($action == "update_hotel")
	{
		// $debug = 1;
		ubd_gi_update_hotel($debug, $hotelID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL, $photo);
		if ($hotelBookingReference != "")
			ubd_gi_update_hotel_booking_reference($debug, $gigID, $hotelBookingReference);
		
		$debug = 0;
		ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "");	   
	}	
	// ======================================== TRAVEL/TRANSPORTATION
	else if ($action == "print_add_travel_form")
	{
		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "ADD_TRAVEL");	   
		ubdc_update_menu_frame();
	}
	else if ($action == "add_travel")
	{
		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		ubd_gi_add_travel($debug, $gigID, $typeOfTransportation, $from, $destination, $date, $departureTime, $arrivalTime, $bookingReference, $userID, $travelDocument);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "");	   
	}
	else if ($action == "edit_travel")
	{
		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "EDIT_TRAVEL", $travelID);	   
	}
	else if ($action == "update_travel")
	{
		ubdc_print_page_title("Spelning", "", "$PHP_FILE_NAME?action=print_add_gig_form&bandID=$bandID", "Lägg till spelning...", $bandID);
		ubd_gi_update_travel($debug, $gigID, $travelID, $typeOfTransportation, $departureFrom, $destination, $date, $departureTime, $arrivalTime, $bookingReference, $userID, $travelDocument);
	    ubd_gi_view_gig($debug, $bandID, $gigID, $PHP_FILE_NAME, "", $travelID);	   
	}
	// ========================================
	//   BAND MEMBER MANAGEMENT ubd_bm_
	// ======================================== The functions reside in util_band_member.php ===
	else if ($action == "print_add_bandmember_form")
	{
		ubdc_print_page_title("Lägg till bandmedlem", "", "", "", $bandID);
	    ubd_bm_print_form($debug, $bandID, $edit, $bandMemberID, $targetPHP);
	}
	else if ($action == "add_bandmember")
	{	
	//	ubdc_print_page_title("Lägg till bandmedlem", "", "$PHP_FILE_NAME?action=print_add_bandmember_form&bandID=$bandID", "Lägg till bandmedlem...", $bandID);
	//	$debug = 1;
	    $newBandMemberID = ubd_bm_add($debug, $bandID, $bandMemberID, $firstName, $lastName, $streetAddress, $postalCode, $city, $phoneWork, $phoneHome, $phoneCellular, $emailWork, $emailPrivate, $instrumentCBoxes ); 
	//	ubd_bm_view($debug,  $newBandMemberID);	  
		ubdc_print_start_page($debug, $bandID, $newBandMemberID, $PHP_FILE_NAME, $stateFlag, $PREVIOUS_ACTION);    
	}
	else if ($action == "view_bandmember")
	{
		// NOT USED
		// This is used ubdc_print_start_page($debug, $bandID, $bandMemberID, $PHP_FILE_NAME, $stateFlag, $MY_PHP_PARAM_LIST);
		// ubdc_print_page_title("Bandmedlem", "", "$PHP_FILE_NAME?action=print_add_bandmember_form&bandID=$bandID", "Lägg till bandmedlem...", $bandID);
		// ubd_bm_view($debug,  $bandMemberID);	    
	}
	else if ($action == "edit_bandmember")
	{
		ubdc_print_page_title("Redigera Bandmedlem", "", "$PHP_FILE_NAME?action=print_add_bandmember_form&bandID=$bandID", "Lägg till bandmedlem...", $bandID);
		//ubd_bm_view($debug,  $bandMemberID);	
		ubdc_print_start_page($debug, $bandID, $userID, $PHP_FILE_NAME, "EDIT", $PREVIOUS_ACTION);    
	}
	else if ($action == "print_edit_bandmember_form")
	{
	    ubd_bm_print_form($debug, $bandID, 1, $bandMemberID);
	}
	else if ($action == "update_bandmember")
	{
	    ubd_bm_update( $debug, $bandID, $userID, $firstName, $lastName, $streetAddress, $postalCode, $city, $phoneWork, $phoneHome, $phoneCellular, $emailWork, $emailPrivate, $image, $logo); 	
		ubdc_print_start_page($debug, $bandID, $userID, $PHP_FILE_NAME, $stateFlag, $PREVIOUS_ACTION);    
	}
	else if ($action == "delete_band_member")
	{
		$firstName = ubdc_get_firstname($bandMemberID);
		$lastName = ubdc_get_lastname($bandMemberID);
		ubdc_print_page_title("Ta bort bandmedlem '$firstName $lastName'", "", "$PHP_FILE_NAME?action=print_add_bandmember_form&bandID=$bandID", "Lägg till bandmedlem...", $bandID);
		$DeleteText = "Do you really want to delete band member '$firstName $lastName'?";
		$ConfirmAction = "do_delete_bandmember&bandMemberID=$bandMemberID";
		$CancelAction = "view_start_page&bandMemberID=$bandMemberID";
		ubdc_print_confirm_deletion_form($debug, $bandID, $DeleteText, $PHP_FILE_NAME, $ConfirmAction, $CancelAction);
	}
	else if ($action == "do_delete_bandmember")
	{
	    ubd_bm_delete($debug, $bandID, $bandMemberID);
		ubdc_print_page_title("Startsida", "", "", "", $bandID);
	    ubdc_print_start_page($debug, $bandID, "", $PHP_FILE_NAME, $stateFlag, $PREVIOUS_ACTION);
	}
	// ========================================
	//   VIDEOS ubd_vi_
	// ======================================== The functions reside in util_band_member.php ===
	else if ($action == "print_add_band_video_form")
	{
		//ubdc_print_page_title("Lägg till video", "", "$PHP_FILE_NAME?action=view_band_videos&bandID=$bandID", "Avbryt", $bandID, "CANCEL");
		ucss_section_start($debug, "section3", $class);
	    ubd_vi_print_form($debug, $PHP_FILE_NAME, $bandID, $edit, $bandMemberID, $targetPHP);
		ucss_section_end($debug);
	   // uvi_print_add_form();
	}
	else if ($action == "add_band_video")
	{	
		ubdc_print_page_title("Lägg till video", "", "$PHP_FILE_NAME?action=print_add_video_form&bandID=$bandID", "Lägg till video...", $bandID);
		ubd_vi_add($debug, $bandID, $gigID, $title, $URL  );
		ubdc_update_menu_frame();
 		ubd_vi_view_all($debug, $bandID, $targetPHP);
	}
	else if ($action == "view_band_video")
	{
	//	ubdc_print_page_title("View Video", "", "$PHP_FILE_NAME?action=print_add_band_video_form&bandID=$bandID", "Lägg till video...", $bandID);
	//	$debug = 1;
	//	ubd_vi_view_all($debug, $bandID, $targetPHP);
		ucss_section_start($debug, "section3", $class);
		ubd_vi_view($debug,  $bandID, $URL, $targetPHP, $v);	    
		ucss_section_end($debug);
	}
	else if ($action == "view_band_videos")
	{
	//	ubdc_print_page_title("Videos", "", "$PHP_FILE_NAME?action=print_add_band_video_form&bandID=$bandID", "Lägg till video...", $bandID);
	//	$debug = 1;
		ucssr_print_header_title("VIDEOS");
		ucss_section_start($debug, "section3", $class);
		ubd_vi_view_all($debug, $bandID, $targetPHP);
		ucss_section_end($debug);
		//ubd_vi_view($debug,  $videoID);	    
	}
	else if ($action == "print_edit_video_form")
	{
		ucss_section_start($debug, "section3", $class);
	    ubd_vi_print_form($debug, $bandID, 1, $bandMemberID);
		ucss_section_end($debug);
	}
	else if ($action == "update_video")
	{
		ucss_section_start($debug, "section3", $class);
	    ubd_vi_update( $debug, $bandID, $bandMemberID, $firstName, $lastName, $streetAddress, $postalCode, $city, $phoneWork, $phoneHome, $phoneCellular, $emailWork, $emailPrivate   ); 	
		ucss_section_end($debug);
	}
	else if ($action == "delete_video")
	{
		ucss_section_start($debug, "section3", $class);
	    ubd_vi_delete($debug, $bandID, $bandMemberID);
		ubdc_update_menu_frame();
		ucss_section_end($debug);
	}
	// =====================================================
	// 	DEFAULT
	// =====================================================
	else
	{
		ubdc_print_page_title("Okänt val", "", "", "", $bandID);
		up_error("index.php : unknown action: '$action'");
		echo "<hr>\n";
		uly_print_lyrics_table($debug, $dbase, $tableName, $targetPHP, $titleFilter, $artistFilter, $selectedInstrument, $bandID);
	}
	ucss_section_start($debug, "footerSection", $class);
	up_page_footer($REMOTE_ADDR, $_SERVER['HTTP_USER_AGENT'], $THE_FILE_AND_PATH, $MY_PHP_PARAM_LIST, $LastUpdate );
	up_print_page_errors( $PHP_FILE_NAME );
	ucss_section_end($debug);
?>
<!-- ==================================================================== PHP PHP PHP PHP-->
 
</body>


</html>
