<?php

/**/
function ubdc_macify($text)
{
	$text = mb_convert_encoding($text, 'UTF-8', 'macintosh');
	$text = iconv('UTF-8', 'macintosh', $text);
	return $text;
}

// ========================================================================
//	JAVASCRIPTS used by ub_band_mgmt
// ========================================================================

function ubdc_print_javascript($debug, $bandID)
{
	
	echo "\n<script language=JavaScript>\n\n"; 
	
	// ====================================
	// DYNAMIC JAVASCRIPT DATA ACCESS (BANDMEMBER DATA)
	// ====================================
	echo "function handleOptionMenuSelection()\n{\n\t";
	echo "var namnet = document.getElementById(\"bmID\");\n\t";
	
	echo "switch (parseInt(namnet.value))\n\t";
	echo "{\n\t";
	// RESET - upon "Befintliga musiker
	echo "case -1:\n\t\t";
	echo "this.document.band_member_form.firstName.value = \"\";\n\t\t";
	echo "this.document.band_member_form.lastName.value = \"\";\n\t\t";
	echo "this.document.band_member_form.streetAddress.value = \"\";\n\t\t";
	echo "this.document.band_member_form.postalCode.value = \"\";\n\t\t";
	echo "this.document.band_member_form.city.value = \"\";\n\t\t";
	echo "this.document.band_member_form.phoneWork.value = \"\";\n\t\t";
	echo "this.document.band_member_form.phoneHome.value = \"\";\n\t\t";
	echo "this.document.band_member_form.phoneCellular.value = \"\";\n\t\t";
	echo "this.document.band_member_form.emailPrivate.value = \"\";\n\t\t";
	echo "this.document.band_member_form.emailWork.value = \"\";\n\t\t";
	echo "this.document.band_member_form.bandMemberID.value = \"-1\";\n\t\t";
	//echo "this.document.band_member_form.image.value = \"$picture\";\n\t\t";
 	echo "this.document.getElementById(\"image\").src= \"\";\n\t\t";
	echo "break;\n\t";
	
	// LIST All stored musicians
//	$Max = ubd_get_nof_musicians();
//	for($i = 0; $i < $Max || $i < 10;$i++)
//	{
	$query = "SELECT * FROM bands.band_member";
	
	$result = mysql_query($query);

    while ($row = mysql_fetch_array ($result))
    {
		$i = $row[id];
		echo "case $i:\n\t\t";
		//GetDataItem($i, $name, $email, $phoneNo); // Get Name, E-mail, PhoneNo
		ubd_get_band_member_data($i, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture);

		echo "this.document.band_member_form.firstName.value = \"$firstName\";\n\t\t";
		echo "this.document.band_member_form.lastName.value = \"$lastName\";\n\t\t";
		echo "this.document.band_member_form.streetAddress.value = \"$streetAddress\";\n\t\t";
		echo "this.document.band_member_form.postalCode.value = \"$postalCode\";\n\t\t";
		echo "this.document.band_member_form.city.value = \"$city\";\n\t\t";
		echo "this.document.band_member_form.phoneWork.value = \"$phoneWork\";\n\t\t";
		echo "this.document.band_member_form.phoneHome.value = \"$phoneHome\";\n\t\t";
		echo "this.document.band_member_form.phoneCellular.value = \"$phoneCellular\";\n\t\t";
		echo "this.document.band_member_form.emailPrivate.value = \"$emPrivate\";\n\t\t";
		echo "this.document.band_member_form.emailWork.value = \"$emWork\";\n\t\t";
		echo "this.document.band_member_form.bandMemberID.value = \"$i\";\n\t\t";
		//echo "this.document.band_member_form.image.value = \"$picture\";\n\t\t";
 		echo "this.document.getElementById(\"image\").src= \"$picture\";\n\t\t";
		echo "break;\n\t";
	}
	echo "default:\n\t\tthis.document.data_form.name.value = parseString(\"Item not found\");\n";
	// END OF SWITCH
	echo "\t}\n\t";
	// END OF FUNCTION
	echo "\n}\n\n";
	// ========================================================================
	// UPDATE MENU FRAME (upon adding songs, rehersal, and or gig dates)
	// ========================================================================
	echo "function updateMenuFrame()\n{\n\t";
//	echo "alert(\"Updating frame\");\n";
	echo "window.top.subtypes$bandID.location='ub_dynamicMenu.php?bandID=$bandID';\n";
//	echo "alert(\"Updating frame DONE!\");\n";
	echo "\n}\n\n";
	// =================================================================
	echo "</script>\n"; 
}

function ubdc_update_menu_frame()
{
	echo '<script> updateMenuFrame(); </script>';
}

// ========================================================================
//	JAVASCRIPTS used by ub_lyrics
// ========================================================================

function ubdc_print_javascript2($debug, $bandID, $songID, $dbase, $tableName)
{
	// $debug =1;
	if ($debug) up_info( "ubdc_print_javascript2(dbug:'$debug', bandID:'$bandID', songID:'$songID', dbase:'$dbase', table:'$tableName')");

	if (!$debug)
		echo "\n<script language=JavaScript>\n\n"; 
	// ========================================================================
	// UPDATE MENU FRAME (upon adding songs, rehersal, and or gig dates)
	// ========================================================================
	echo "function updateMenuFrame()\n{\n\t";
//	echo "alert(\"Updating frame\");\n";
	echo "window.top.subtypes$bandID.location='ub_dynamicMenu.php?bandID=$bandID';\n";
	echo "\n}\n\n";
	
	if ($songID != "")
	{
		// ========================================================================
		// 		SHOW CHORDS (or not)
		// ========================================================================
		echo "function js_showChords()\n{\n\t";
		// echo "alert(\"js_showChords :Inside\");\n";
		// $debug = 1;
//  	uly_get_lyrics_data($debug, $dbase, $tableName, $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize);
		uly_get_lyrics_only($debug, $dbase, $tableName, $songID, $bandID, &$lyrics);
		if ($debug) echo "Lyrics: $lyrics";
		uly_get_lyrics_chords_separated($lyrics, &$newChords, &$noChordsLyrics);
		echo "if (this.document.cboxForm.showChordsCBox.checked==true)\n\t\t";
		echo "    this.document.textForm.lyrics.value = \"$lyrics\"\n\t";
		echo "else\n\t\t";
		echo "    this.document.textForm.lyrics.value = \"$noChordsLyrics\"\n\t";
		echo "\n}\n\n";
	}
	// =================================================================
	echo "</script>\n"; 

}

function ubdc_print_confirm_deletion_form($debug, $bandID, $DeleteText, $PHP_FILE_NAME, $ConfirmAction, $CancelAction)
{
	$URL = "$PHP_FILE_NAME?" . "bandID=$bandID&action=";
	echo "<h3>$DeleteText</h3>";
	ub_check($URL . $ConfirmAction);
	ub_cancel($URL . $CancelAction);
}

function ubdc_get_unique_id($debug, $database, $table, $uID_name)
{
    $query = "SELECT MAX($uID_name) AS MaxUID FROM $database.$table";
    $result = mysql_query ($query);	

    $row = mysql_fetch_array ($result);
   
    $maxUID = $row[MaxUID];
    if ($maxUID == "")
		return 1;		// START at 1.
    else
    {
		$maxUID = $maxUID + 1;
       return $maxUID;
    }
}
// 
function ubdc_create_previous_action($MY_PHP_PARAM_LIST, $PHP_FILE_NAME)
{
	// Remove initial '?'
	$PREVIOUS_ACTION  = str_replace("?", "QM", $MY_PHP_PARAM_LIST);
	// Add targetPHP in front
	$PREVIOUS_ACTION  = $PHP_FILE_NAME . $PREVIOUS_ACTION;
	//
	$PREVIOUS_ACTION  = str_replace("action", "prev_action", $PREVIOUS_ACTION);
	// Replace '=' with - AND '&' with AND
	$PREVIOUS_ACTION  = str_replace("=", "EQUALS", $PREVIOUS_ACTION);
	$PREVIOUS_ACTION  = str_replace("&", "AND", $PREVIOUS_ACTION);
	return $PREVIOUS_ACTION;
}

function ubdc_convert_previous_action($PREVIOUS_ACTION, $PHP_FILE_NAME)
{
	// Add initial ?
	// $PREVIOUS_ACTION = "?" . $PREVIOUS_ACTION;
	$PREVIOUS_ACTION  = str_replace("QM", "?", $PREVIOUS_ACTION);
	$PREVIOUS_ACTION  = str_replace("EQUALS", "=", $PREVIOUS_ACTION);
	$PREVIOUS_ACTION  = str_replace("AND", "&", $PREVIOUS_ACTION);
	$PREVIOUS_ACTION  = str_replace("prev_action", "action", $PREVIOUS_ACTION);
	return $PREVIOUS_ACTION;
}

function ubdc_get_title_data($debug, $targetPHP, $bandID, &$bandName, $action, &$pageTitle, &$addAction, &$editAction)
{
	ubdc_get_band_data($debug, $level, $bandID, &$bandName, &$bandDB, &$bandLogo);
	$pageTitle = $bandName;
	return;							// FIXME - Ignore titles
	$pageTitle = $bandName . " - ";
	switch($action)
	{
	case search:
	case "":
	case view_start_page:
		$pageTitle = $bandName;
		//$pageTitle = $pageTitle . "Startsida" ;
		break;
	// ===============================
	// 		LÅTAR
	// ===============================
	case view_next_rehersal_songs:
		$pageTitle = $pageTitle . "Nästa rep" ;
		break;	
	case view_rehersed_songs:
		$pageTitle = $pageTitle . "Inrepade låtar" ;
		break;
	case view_proposed_songs:
		$pageTitle = $pageTitle . "Låtförslag";
		break;
	case view_all_lyrics:
		$pageTitle = $pageTitle . "Alla låtar";
		break;
	case add_lyrics:
		$pageTitle = $pageTitle . "Lägg till låt";
		break;
	case edit_lyric:
		$pageTitle = $pageTitle . "Redigera låt";
		break;
	case import_lyrics:
	case filter_import_lyrics:
		$pageTitle = $pageTitle . "Importera låt";
		break;
	case update_lyric:
	case view_lyric:
		$pageTitle = $bandName;
		$addAction = "$targetPHP?bandID=$bandID&action=add_lyrics";
		break;
	case filter_lyrics:
		$pageTitle = $bandName - "Söker";
		$addAction = "$targetPHP?bandID=$bandID&action=add_lyrics";
		break;
	// ===============================
	// 		MP3 - SPOTIFY - VIDEOS - MIDI - PLAYBACKS
	// ===============================
	case do_add_playback:
	case view_mp3:
	case view_spotify:
	case view_midi:
	case view_playback:
		$pageTitle = $bandName ;
		break;		
	// ===============================
	// 		LÅTLISTOR
	// ===============================
	case delete_repertoir:
	case edit_repertoir:
	case print_copy_repertoir_form:
		$pageTitle = "";
	//	$pageTitle = $bandName;
		break;
	case view_repertoir:
		$pageTitle = $pageTitle . "Låtlista";
		$addAction = "$targetPHP?bandID=$bandID&action=print_add_repertoir_form";
		break;	
	case view_repertoirs:
//		$pageTitle = $pageTitle . "Låtlistor";
		$pageTitle = "";
		$addAction = "$targetPHP?bandID=$bandID&action=print_add_repertoir_form";
		break;	
	case print_add_repertoir_form:
		$pageTitle = $pageTitle . "Lägg till ny låtlista...";
		break;	
	// ===============================
	// 		REP
	// ===============================
	case view_rehersals:
		$pageTitle = $pageTitle . "Kommande rep";
		$addAction = "$targetPHP?bandID=$bandID&action=print_add_rehersal_form";
		break;	
	case print_add_rehersal_form:
		$pageTitle = $pageTitle . "Lägg till nytt rep...";
		break;	
	case edit_rehersal_availability:
		$pageTitle = $pageTitle . "Redigera tillgänglighet";
//		$pageTitle = $bandName;
		break;
	case add_availability:
	case print_rehersal_availability:
		$pageTitle = $pageTitle . "Tillgänglighetschema";
//		$pageTitle = $bandName;
		break;		
	// ===============================
	// 		SPELNINGAR
	// ===============================
	case edit_arena_details:
	case update_arena_details:
	case view_gig:
	case edit_gig:
		$pageTitle = "";
//		$pageTitle = $pageTitle . "Redigera spelning";
		$addAction = "$targetPHP?bandID=$bandID&action=print_add_gig_form";
		break;	
	case view_gigs:
		$pageTitle = "";
//		$pageTitle = $pageTitle . "Spelningar";
		$addAction = "$targetPHP?bandID=$bandID&action=print_add_gig_form";
		break;	
	case print_add_gig_form:
		$pageTitle = "";
//		$pageTitle = $pageTitle . "Lägg till ny spelning...";
		break;	
	// ===============================
	// 		VIDEOS
	// ===============================
	case view_band_videos:
		$pageTitle = $pageTitle . "Videos";
		$addAction = "$targetPHP?bandID=$bandID&action=print_add_band_video_form";
		break;	
	case print_add_band_video_form:
		$pageTitle = $pageTitle . "Lägg till ny video...";
		break;	
	// ===============================
	// 		DEFAULT
	// ===============================
	default:
		$pageTitle = $pageTitle . "ubdc_get_title_data: <br>Title unknown for action '$action'";
	}
}


// ===================================
//			E-MAIL
// ===================================

function ubdc_email_bandmembers($debug, $bandID, $eventType, $date, $time)
{
	// $debug = 1;
	if ($debug) up_note("ubdc_email_bandmembers($debug, $bandID, $eventType, $date, $time): ENTER");
	// FIXME
	$to = "patrik.strand@yahoo.com";
	up_warning("The notification mail was only sent to '$to'.");
	$subject = "$bandID: Nytt $eventType $date kl. $time.";
	$message = "Hej! Nytt $eventType är bokat $date kl $time. Mvh. admin.";

	$from = "admin@the-strands.com";

	$headers = "From:" . $from;

	$returnValue = mail($to,$subject,$message,$headers);
	if($returnValue == FALSE)
	{
		up_error("ubdc_email_bandmembers: Unable to send e-mail.");
	}
	if ($debug) echo "Mail Sent.";
	if ($debug) up_note("ubdc_email_bandmembers($debug, $bandID, $eventType, $date, $time): ENTER");
}

// ===================================
//			TRAVEL DOCUMENTS
// ===================================

function ubdc_print_transport_type_option_menu($debug, $selectedTransport, $disabled, $tableColumn)
{
	$query = "SELECT * FROM bands.transportationTypes ORDER BY name";

	$name = "typeOfTransportation";
	up_select_header($debug, $targetPhp, $name, $identity, $callItself, $onChangeFunctionName, $tableColumn, $colSpan, $bgcolor, $tableAlign);
	
    $result = mysql_query ($query);	

	$i = 0;
    while ($row = mysql_fetch_array ($result))
	{
		up_select_menu_item($debug, $row[name], $row[uID], $disabled, $selectedTransport);
	}	
	up_select_footer($debug, $tableColumn, $callItself);
}

// ===================================
//			ARENA option menu
// ===================================

function 	ubdc_get_arena_data($debug, $dbase, $arenaUID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL)
{
	$sql = "SELECT * FROM bands.arena WHERE uID='$arenaUID'";
    $result = mysql_query ($sql);	

	$i = 0;
    while ($row = mysql_fetch_array ($result))
    {
    	$name 			= $row[name];
    	$streetAddress	= $row[streetAddress];
    	$city			= $row[city];
    	$postalCode		= $row[postalCode];
    	$phoneNumber	= $row[phoneNumber];
    	$URL			= $row[URL];
    	return;
    }
   // $name = "Arena '$arenaUID' not found.";
}

/*
function 	uly_get_arena_name($debug, $dbase, $arenaUID)
{
	$sql = "SELECT * FROM $dbase.arena WHERE uID='$arenaUID'";
    $result = mysql_query ($sql);	

	$i = 0;
    while ($row = mysql_fetch_array ($result))
    {
    	return $row[name];
    }
    return "$arenaUID-cat.not found";
}
*/

function 	uly_does_arena_exist($debug, $name)
{
	if ($debug) up_note("uly_does_arena_exist($debug, $dbase, $languange): ENTER");

	$sql = "SELECT * FROM bands.arena WHERE name='$name'";
    	$result = mysql_query ($sql);	

	$i = 0;
    	while ($row = mysql_fetch_array ($result))
    	{
		if ($debug) up_note("TRUE - EXIST!");
		return TRUE;
       }
	if ($debug) up_note("FALSE - DOES NOT Exist!");
	return FALSE;
}

function 	ubdc_add_arena($debug, $name, $streetAddress, $city, $URL, $postalCode, $phoneNumber)
{
	if ($debug) up_note("uly_add_arena(dbug:$debug, name:$name, $streetAddress, $city, $URL): ENTER");

	if (uly_does_arena_exist($debug, $name))
	{
		up_error("Arena $name already exist.");
		return $arena;
	}
	if ($name == "")
	{
		up_error("ubdc_add_arena: Invalid NULL parameter.");
		return -1;
	}
	$uID = ubdc_get_unique_id($debug, "bands", "arena", "uID");
	
	if ($uID)
	{
		$variables = "uID"; 
		$parameters = "'$uID'"; 	
	}	
	else
	{
		up_error("ubdc_add_arena: Invalid unique identifier.");
		return;
	}
	if ($name)
	{
		$variables = $variables . ", name"; 
		$parameters = $parameters . ", '$name'"; 	
	}
	if ($streetAddress)
	{
		$variables = $variables . ", streetAddress"; 
		$parameters = $parameters . ", '$streetAddress'"; 	
	}
	if ($city)
	{
		$variables = $variables . ", city"; 
		$parameters = $parameters . ", '$city'"; 	
	}
	if ($URL)
	{
		$variables = $variables . ", URL"; 
		$parameters = $parameters . ", '$URL'"; 	
	}
	if ($postalCode)
	{
		$variables = $variables . ", postalCode"; 
		$parameters = $parameters . ", '$postalCode'"; 	
	}
	if ($phoneNumber)
	{
		$variables = $variables . ", phoneNumber"; 
		$parameters = $parameters . ", '$phoneNumber'"; 	
	}
    $sql = "INSERT INTO bands.arena ( $variables ) VALUES ( $parameters ) ";
	if($debug) up_note("sql: <b>$sql</b>");

	$result = mysql_query ($sql);
   	if($result != 1)
   	{
		up_error( "ubdc_add_arena: Failed to ADD the $uID/$name in the database.");
		$msg = mysql_error();	
		up_error( $msg);
		return -1;
   	}

	if ($debug) up_note("uly_add_arena($debug, $dbase, $name): EXIT");
	return $uID;
}

function 	ubdc_gi_update_arena( $debug, $arenaID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL, $photo )
{
	if ($arenaID == "" || $arenaID < 0)
	{
		// up_error("ubdc_gi_update_arena: Illegal NULL parameter (arenaID)");
		// IF MISSING - CREATE and return the new Arena ID.
		$newArenaID = ubdc_add_arena($debug, $name, $streetAddress, $city, $URL, $postalCode, $phoneNumber);
		return $newArenaID;
	}
	// MUST be given (first item in the list of parameters)
	$variables = "uID='$arenaID'"; 

	if ($name)
	{
		$variables = $variables . ", name='$name'"; 
	}
	if ($streetAddress)
	{
		$variables = $variables . ", streetAddress='$streetAddress'"; 
	}
	if ($city)
	{
		$city = up_clean_var($city);
		$variables = $variables . ", city='$city'"; 
	}	
	if ($postalCode)
	{
		$variables = $variables . ", postalCode='$postalCode'"; 
	}	
	if ($phoneNumber)
	{
		$variables = $variables . ", phoneNumber='$phoneNumber'"; 
	}	
	if ($URL)
	{
		$variables = $variables . ", URL='$URL'"; 
	}	
	if ($photo)
	{
		$variables = $variables . ", photo='$photo'"; 
	}	
	$sql = "UPDATE bands.arena SET $variables WHERE uID=$arenaID";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubdc_gi_update_arena: Unable to update the arena. (sql:$sql)");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
    else
    {
    	if ($debug) up_info("Updated arena successfully -  $arenaID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL");
    }
	if ($debug) up_note("ubdc_gi_update_arena: EXIT");		
}	

function 	ubdc_print_arena_option_menu($debug,  $bandID, $targetPHP, $selectedArenaID, $tableColumn, $cityFilter)
{
	// $debug = 1;
	if ($debug) up_note("ubdc_print_arena_option_menu(dbug:$debug, aID:$selectedArenaID, tCol:$tableColumn, $targetPHP, $cityFilter): ENTER");
	
	if ($cityFilter != "")
		$sql = "SELECT * FROM bands.arena WHERE city='$cityFilter' ORDER BY name";	
	else
		$sql = "SELECT * FROM bands.arena ORDER BY name";
    $result = mysql_query ($sql);	
	
	if ($stateFlag== "INVALID_PARAMETERS" &&  $selectedCategory == "")
		$style = "style=\"background-color=#ffbbbb\"";		
	$i = 0;

	$name 		= "arenaID";
	$identity 	= $name;
	$callItself = 1;
	$tableAlign = "left";
$modTargetPHP = $targetPHP . "?bandID=$bandID" . "&action=edit_gig";
// $modTargetPHP =$targetPHP;
	up_select_header($debug, $modTargetPHP, $name, $identity, $callItself, $onChangeFunctionName, $tableColumn, $colSpan, $bgcolor, $tableAlign);
//	up_php_param("bandID", $bandID, "hidden");
	
	//echo "<select name=arenaID $style>\n\t";
       echo "<option value=\"\" selected>Välj plats\n\t";
   	while ($row = mysql_fetch_array ($result))
    {
		if ($selectedArenaID == $row[uID])
               	echo "<option value=$row[uID] selected>$row[name] ($row[city])\n\t";
		else
            	echo "<option value=$row[uID]>$row[name] ($row[city])\n\t";
		$i++;
    }
    up_select_footer($debug, $tableColumn, $callItself);

	if ($debug) up_note("ubdc_print_arena_option_menu($debug, $dbase): EXIT");	
}
/**/
// 	ubdc_print_arena_option_menu_form($debug, $bandID, $targetPHP, $selectedPlace, 1, $disabled, $stateFlag);

function 	ubdc_print_new_arena_item_form($debug, $targetPHP, $errorFlag, $selectedArena, $bandID, $gigID)
{
	if ($debug) up_info("ubdc_print_new_arena_item_form($targetPHP, $errorFlag, $selectedArena, $bandID, $gigID)");
	if ($errorFlag) 
		$bgcolor = "#ffaaaa";
	else
		$bgcolor = "#bbffbb";
	if ($selectedArena != "-1" && $selectedArena != "")
	{
		$readOnly = 1;
		ubdc_get_arena_data($debug, $dbase, $selectedArena, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL);
	}
	else // create arena
	{
		up_form_footer();
		up_form_header($targetPHP);
	}
	$tableColumn = 1;
	$icolSpan = 5;
	$isize = 50;
	$align = "right";
	$valign = "baseline";
	up_php_param("action", "add_arena", "hidden");
	up_php_param("bandID", $bandID, "hidden");
	up_new_table_row();
	up_table_column("Namn:", 1, $align, $size, $valign, $open, $colspan, $underline, $nobgcolor, $width, $fontsize);

	up_php_param("name", $name, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	// ub_edit("$targetPHP?action=edit_arena&bandID=$bandID&gigID=$gigID&arenaID=$selectedArena", $height, $target, "Redigera arena", $tableColumn, $right, $valign, $columnWidth, "left", "#ffffff");
	up_new_table_row();
	up_table_column("Gatuadress:", 1, $align, $size, $valign, $open, $colspan, $underline, $nobgcolor, $width, $fontsize);
	up_php_param("streetAddress", $streetAddress, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("Stad:", 1, $align, $size, $valign, $open, $colspan, $underline, $nobgcolor, $width, $fontsize);
	up_php_param("city", $city, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("URL:", 1, $align, $size, $valign, $open, $colspan, $underline, $nobgcolor, $width, $fontsize);
	up_php_param("URL", $URL, "", $tableColumn, $isize+50, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
// ($URL, $height, $target, $title, $tableColumn, $right, $valign, $columnWidth)
	up_new_table_row();
	if ($selectedArena == "-1"  || $selectedArena == "")
	{
		up_table_column("");
		echo "<td>\n";
		ub_save("", $height, $target, $title, !$tableColumn, $right, $valign, $columnWidth);
		up_form_footer();
		ub_cancel("$targetPHP", 20);
		echo "</td>\n";
	}
	else
	{
		// up_info("No save");	
	}
}

function 	ubdc_print_arena_option_menu_form($debug, $bandID, $targetPHP, $selectedArena, $tableColumn, $disabled, $stateFlag, $givenTitle, $givenArtist, $givenCategory, $givenarena, $gigID)
{
	//$debug = 1;
	if($debug) up_note("ubdc_print_arena_option_menu_form(dbug:$debug, bID:$bandID, sArena:$selectedArena, tCol:$tableColumn, dis:$disabled, sFlag:$stateFlag, gTitle:$givenTitle, gArti:$givenArtist, gCat:$givenCategory, $gigID): ENTER", 2);

	if ( $targetPHP == ""|| $stateFlag == "")
	{
		up_error("ubdc_print_arena_option_menu_form: Invalid NULL parameters.");
		return;
	}
	if ($stateFlag== "INVALID_PARAMETERS" && $selectedCategory == "")
		$bg = "bgcolor=#ff0000";
	if ($stateFlag == "EDIT")
		$targetPHP = $targetPHP . "?action=edit_gig&bandID=$bandID&gigID=$gigID";
	if ($stateFlag != "PREPARE_ADD")
		ubdc_print_arena_option_menu($debug,  $bandID, $targetPHP, $selectedArena, $tableColumn,  $cityFilter);
	else
		up_table_column("Ny arena...");
	if ($stateFlag== "ADD" || $stateFlag== "INVALID_PARAMETERS" || $stateFlag== "PREPARE_ADD_CATEGORY") //  || $stateFlag== "EDIT" )
	{
		ub_add("$targetPHP?action=prepare_add_arena&bandID=$bandID", 20, "_self", "Add arena...", $tableColumn);
		//  up_php_param("action", "do_add_lyrics", "hidden", 0);
	}
	else
	{
		ubdc_print_new_arena_item_form($debug, $targetPHP, $errorFlag, $selectedArena, $bandID, $gigID);
    }

	if ($tableColumn)
		echo "</td>\n";
}

function	ubdc_print_add_building_form($debug, $targetPHP, $action, $bandID, $cancelAction, $city, $gigID)
{
	if ($debug) up_info( "ubdc_print_add_building_form($debug, $targetPHP, $action, $bandID, $cancelAction): ENTER");
	up_form_header($targetPHP);
	//$i++; up_note("bp $i");
	up_php_param("action", $action, "hidden");
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("gigID", $gigID, "hidden");
	up_table_header();$tableColumn =1;$align="right"; $valign="bottom";
	up_table_column("Hotell:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_php_param("name", $name, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("Gatuadress:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_php_param("streetAddress", $streetAddress, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("Stad:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_php_param("city", $city, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("Telefon:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_php_param("phoneNumber", $phoneNumber, "", $tableColumn, $isize, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("URL:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_php_param("URL", $URL, "", $tableColumn, $isize+50, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $icolSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign);
	up_new_table_row();
	up_table_column("");
	echo "<td>";
	ub_save();
	ub_cancel("$targetPHP?action=$cancelAction&bandID=$bandID&gigID=$gigID");
	echo "</td>";
	up_table_footer();
	up_form_footer();
}

// ============================================
//	GET BAND DATA/ DATABASE
// ============================================

function ubdc_print_page_icon($debug, $iconFileName)
{
	if ($iconFileName == "")
	{
		$iconFileName  = "../common/images/icons/music-note.png";
	}

	$fileType = uf_get_file_suffix($debug, $iconFileName);
	echo "<link rel='icon' type='image/$fileType' href='$iconFileName'> ";
}
// NOTE> The given table must have a "uID" column OR given Unique ID label (parameter 4).

// FIXME
function ubdc_print_page_title($pageTitle, $subTitle, $addURL, $addHoverText, $bandID, $icon, $targetPHP)
{
	// $debug = 1;
	if ($debug) up_note("ubdc_print_page_title($pageTitle, $subTitle, $addURL, $addHoverText, bandID:$bandID): ENTER");
	ubdc_get_band_data($debug, $level, $bandID, &$bandName, &$bandDB, &$bandLogo);
	$tableColumn = 1;
	$valign= "center";
	up_table_header(0);
	
	if ($subTitle != "")
		$title = $pageTitle . " - " . $subTitle;
	else 
		$title = $pageTitle;
//	if (strlen($title) < 20)
		$fontsize = 7;
//	else
//		$fontsize = 6;
	if ($addURL != "")
	{
		up_table_column($title, 1, "left", "", "baseline", 1, 0, $underline, $bgcolor, 620, $fontsize);
		if ($icon == "")
			ub_add( $addURL, 20, "", $addHoverText, 0, "baseline");
		else
		{
			switch ($icon)
			{
			case "SEARCH": ub_search($addURL, 20, "", $addHoverText, 0, "baseline"); break;
			case "ADD": ub_add( $addURL, 20, "", $addHoverText, 0, "baseline"); break;
			case "CANCEL": ub_cancel( $addURL, 20, "", $addHoverText, 0, "baseline"); break;
			case "EXPAND": ub_expand( $addURL, 20, "", $addHoverText, 0, "baseline"); break;
			case "COLLAPSE": ub_collapse( $addURL, 20, "", $addHoverText, 0, "baseline"); break;
			
			}
		
		}
		echo "</td>\n";
	}
	else
	{
		up_table_column($title, 1, "left", "", "baseline", 0, 1, $underline, $bgcolor, 600, $fontsize);
	}
	if ($icon == "SEARCH")
	{
		up_form_header($targetPHP);
		up_php_param("action", "search", "hidden");
		up_php_param("bandID", "$bandID", "hidden");
		up_php_param("searchItem", "", "", $tableColumn);
		ub_search($URL, $height, $target, $title, $tableColumn, $right, $valign, $columnWidth);
		up_form_footer();
	}
	if ($bandID != "" && $bandLogo != "")
		echo "<td  align=right valign=top><img src=$bandLogo height=50 valign=baseline></td>\n\t";
	up_table_footer();
	echo "<hr>\n";
}

function ubdc_print_nice_title($debug, $title, $targetPHP, $action, $bandID, $icon)
{
	//$debug = 1;
	if ($debug) up_note("ubdc_print_nice_title($debug, $title, $targetPHP, $action, $bandID): ENTER");
	echo "<!-- ========== NICE TITLE START =========== -->";
	$tableColumn = 0;
	up_table_header();
	if ($editAction)
		$newtitle = $title . ub_edit($editAction, $height, $target, $title, $tableColumn, $right, $valign, $columnWidth);
	else
		$newtitle = $title;
	// up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)
	if ($targetPHP != "" && $action != "")
	{
		$open = 1;
		up_table_column($newtitle, 1, "center", $size, "baseline", $open, 1, 0, "#adcdfe", 745, "5"); // grey=eeeee
		switch ($icon)
		{
		case "ADD" : ub_add("$targetPHP?action=$action&bandID=$bandID"); break;
		case "EDIT" : ub_edit("$targetPHP?action=$action&bandID=$bandID"); break;
		}
		echo "</td>";
	}
	else
	{
		$open = 0;
		up_table_column($newtitle, 1, "center", $size, "baseline", $open, 1, 0, "#adcdfe", 745, "5"); // grey=eeeee
	}
	up_table_footer();
	echo "<!-- ========== NICE TITLE END =========== -->";
}
// ============================================
//	PRINT BAND MEMBERS
// ============================================

// stateFlag : VIEW, ADD or EDIT (default VIEW)
function ubdc_print_band_member($debug, $bandID, $userID, $targetPHP, $stateFlag)
{
	if (!$targetPHP)
		$targetPHP = "ub_band_mgmt.php";
	if ($debug) up_note("ubdc_print_band_member($debug, bID:$bandID, uID:$userID): ENTER", 2);
	if($userID == "")
	{
		up_error("ubdc_print_band_member: Invalid NULL parameter.");
		return;
	}
	ubd_get_band_member_data($userID, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture, &$logo);

	if ($stateFlag == "EDIT")
	{
		up_form_header($targetPHP);
		up_php_param("action", "update_bandmember", "hidden");
		up_php_param("bandID", "$bandID", "hidden");
		up_php_param("userID", "$userID", "hidden");
	}
	$tableColumn = 1;
	up_table_header(0);
	// ub_cancel($URL, $height, $target, $title, $tableColumn, $right, $valign, $columnWidth, $align, $bgcolor)
	ub_cancel("$targetPHP?action=view_start_page&bandID=$bandID", 20, $target, "Go back", 1, $right, $valign, $columnWidth, $align, $bgcolor);
// up_image($image, $width, $height, $tableColumn, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, $identity)
	$rowSpan = 9;
	up_image($picture,$width, 400,1, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, $identity);	
	// NAMN
	$colspan = 2; $valign="bottom";
	up_table_column("Namn:", 1, "right");
	if ($stateFlag == "")
		up_table_column("$firstName $lastName");
	else if ($stateFlag == "EDIT")
	{
		$colSpan = 1;
		$size = 10;
		up_php_param("firstName", "$firstName", $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
		$colSpan = 3;
		up_php_param("lastName", "$lastName", $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
	}
	up_new_table_row();
	// GATUADRESS
	// up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)
	up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
	up_table_column("Adress:", 1, "right");
		$size = 20;
	$colSpan = 4;
	if ($stateFlag == "")
		up_table_column("$streetAddress");
	else if ($stateFlag == "EDIT")
		up_php_param("streetAddress", "$streetAddress", $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
	up_new_table_row();
	// POST ADRESS
	up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
	if ($stateFlag == "")
	{
		up_table_column("", 1);
		up_table_column("$postalCode $city");
	}
	else if ($stateFlag == "EDIT")
	{
		up_table_column("Postadress:", 1, "right");
		up_php_param("postalCode", "$postalCode", $type, $tableColumn, 7);
		up_table_column("Stad:", 1, "right");
		up_php_param("city", "$city", $type, $tableColumn, 20);	
	}
	up_new_table_row();
	// TELEFON
	up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
	up_table_column("Mobil:", 1, "right");
	if ($stateFlag == "")
		up_table_column("$phoneCellular");
	else if ($stateFlag == "EDIT")
		up_php_param("phoneCellular", "$phoneCellular", $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
	up_new_table_row();
	// E-mail WORK
	up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
	up_table_column("E-post (arbete):", 1, "right");
	if ($stateFlag == "")
		up_table_column("$emWork");
	else if ($stateFlag == "EDIT")
	{
		$size = 30;
		$colSpan = 4;
		up_php_param("emailWork", "$emWork", $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
	}
	up_new_table_row();
	// E-mail PRIVAT
	up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
	up_table_column("E-post (privat):", 1, "right");
	if ($stateFlag == "")
	{
		up_table_column("$emPrivate");
	}
	else if ($stateFlag == "EDIT")
	{
		$size = 30;
		$colSpan = 4;
		up_php_param("emailPrivate", "$emPrivate", $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
	}
	// FRONT IMAGE
	up_new_table_row();
	up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
	if ($stateFlag == "EDIT")
	{
		up_table_column("Bild:", 1, "right");
		$size = 30;
		$colSpan = 4;
		up_php_param("image", "", "file", $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
	}
	// LOGO
	up_new_table_row();
	if ($logo)
	{
		up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
		echo "<td colspan=4><img src=$logo width=300></td>";	
	    if ($stateFlag == "EDIT")
		{
			up_new_table_row();
			up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
			up_table_column("Logo:", 1, "right");
			$size = 30;
			$colSpan = 4;
			up_php_param("logo", "", "file", $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
		}	
	}
	else
	{
		up_table_column("", $bold, $align, $fsize, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);	
//		if ($stateFlag == "")
//			up_table_column("Saknas", 0, "left");
	 	if ($stateFlag == "EDIT")
		{
			up_table_column("Logo:", 1, "right");
		$size = 30;
			$colSpan = 4;
			up_php_param("logo", "", "file", $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan);
		}	
	}
	$tableColumn = 1;
	up_new_table_row();
	up_table_column("");
	up_table_column("");
	// echo "targetPGP = '$targetPHP'";
	if ($stateFlag == "EDIT")
	{
		echo "<td>";
		ub_save("", 25, "_self", "Uppdatera bandmedlem");
		ub_cancel("$targetPHP?action=view_start_page&bandID=$bandID&bandMemberID=$userID", 25, "_self", "Avbryt");
		ub_delete("$targetPHP?action=delete_band_member&bandID=$bandID&bandMemberID=$userID", 25, "_self", "Ta bort bandmedlem");
		echo "</td>";
			
	}
	else // EDIT BUTTON IS NOW IN THE HEADER
	{
	//	ub_edit("$targetPHP?action=edit_bandmember&bandID=$bandID&userID=$userID", 25, "_self", "Redigera bandmedlem", $tableColumn);
	}
	up_table_footer();
	if ($stateFlag == "EDIT")
		up_form_footer();
	switch ($data)
	{
		case "picture": up_image($picture);	
	}
}

function ubdc_print_band_members($debug, $bandID, $bandMemberID, $targetPHP, $stateFlag)
{
	//$debug = 1;
	if ($debug) up_note("ubdc_print_band_members($debug, bID:$bandID, bmID:'$bandMemberID', php:$targetPHP, sFlag:$stateFlag): ENTER");
	if (!$targetPHP)
		$targetPHP = "ub_band_mgmt.php";
	
	if ($bandMemberID != "")
	{
		ubdc_print_band_member($debug, $bandID, $bandMemberID, $targetPHP, $stateFlag);
		return;
	}
//	ub_add("$targetPHP?action=print_add_bandmember_form&bandID=$bandID");
	$nofmembers = ubd_get_nof_bandmembers($debug, $bandID);
//	echo "bandmembers: $nofmembers";
	// PRINT IMAGE
	$query = "SELECT * FROM bands.band_members WHERE bandID='$bandID'";
  	$result = mysql_query ($query);

	$AllEmailAddresses = "";

	$iNofBandMembers=0;

	// GET DATA
	
	while ($row = mysql_fetch_array ($result))
  	{	
  		if (ubd_bm_exist($debug, $row[userID]))
  		{
 			ubd_get_band_member_data($row[userID], &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture);
 	
			$fName[$iNofBandMembers]    	= $firstName;     
			$lName[$iNofBandMembers]     	= $lastName;     
			$cellPhone[$iNofBandMembers]    = $phoneCellular;     
			$emailPrivate[$iNofBandMembers] = $emailAddressPrivate;     
			$emailWork[$iNofBandMembers]    = $emailAddressWork;     
			$image[$iNofBandMembers]        = $picture;     
			$userID[$iNofBandMembers]       = $row[userID];     
			$iNofBandMembers = $iNofBandMembers + 1;
		}
		else
		{
			up_warning("User $row[userID] is unknown.");
		}
		if ($debug) up_info( "#$iNofBandMembers bandmember: $firstName $lastName");
	}
	
	if ($iNofBandMembers > 7)
	{
		// ALL ON ONE ROW
		$iAuxNofBandMembers = $iNofBandMembers;
	//	$iNofBandMembers = 4;
	}
	// PRINT DATA
	//echo "<center>";
	if ($debug) up_info( "#$iNofBandMembers bandmembers");
	echo "<table border=0>\n<tr>\n";
	// DRAW IMAGES
	for($i = 0; $i < $iNofBandMembers; $i++)
	{		
	   	if ($image[$i] != "")
	   	{
	   		if ($debug)  up_note("<font color=#ff0000>$image[$i]</font>");
			echo "\t<td width=103 align=center valign=center bgcolor=#000000><!-- BILD -->\n\t";
	   		echo "<!-- $firstName[$i] $lastName[$i] -->\n\t<a href=$targetPHP?action=view_start_page&bandID=$bandID&bandMemberID=$userID[$i]><img src=\"$image[$i]\" title=\"$fName[$i] $lName[$i]\" width=100></a>\n\t</td>";
		}
		else  // TEXT (if picture is missing)
		{
			echo "\t<td width=103 align=center valign=center bgcolor=#000000 fgcolor=#ffff00><!-- BILD -->\n\t";
			// , $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor
			$fontColor = "#ffff00";
			ub_nice_link("$targetPHP?action=view_start_page&bandID=$bandID&bandMemberID=$userID[$i]", "$fName[$i] $lName[$i]", $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor);
	   		// echo "<!-- $firstName[$i] $lastName[$i] -->\n\t<a href=$targetPHP?action=view_start_page&bandID=$bandID&bandMemberID=$userID[$i]><font color=#ffff00>$fName[$i] $lName[$i]</a></font>\n\t</td>";
		}
	}
//	echo "<th rowspan=16 bgcolor=#000000></th>";
	echo "\n</tr>\n</table>";
	//echo "</center>";

	if ($debug) up_note("ubdc_print_band_members($debug, $bandID): EXIT");
}

// ============================================
//	PRINT NEW SONGS
// ============================================

function ubdc_print_spotify_embedded($debug, $URL, $tableColumn, $rowspan)
{
	//up_note( "URL='$URL'");
	// Default width=300 / height=380
	$width 	= 250;
	$height = 80;
	if ($tableColumn) echo "<td rowspan=\"$rowspan\">\n\t";
	if ($URL != "")
		echo "<iframe src=https://embed.spotify.com/?uri=$URL width=$width height=$height frameborder=0 allowtransparency=true></iframe>";
	if ($tableColumn) echo "</td>\n\t";
}

function ubdc_print_grooveshark_embedded($debug, $URL, $tableColumn, $title)
{
	//up_note( "URL='$URL'");
	// Default width=300 / height=380
	$width 	= 250;  
	$height = 80;		// GS=40
	if ($tableColumn) echo "<td>\n\t";
	if ($URL != "")
	{
		// echo "gigya src="http://grooveshark.com/widget.swf" width="250" height="40" wmode="window" allowScriptAccess="always" flashvars="VARIABLES HERE"";
	/*
		echo "<object width=$width height=$height classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" id=\"gsSong3387189767\" name=\"gsSong3387189767\">";
		echo "<param name=movie value=http://grooveshark.com/songWidget.swf />";
		echo "<param name=wmode value=window />";
		echo "<param name=allowScriptAccess value=always />";
		echo "<param name=flashvars value=\"hostname=grooveshark.com&songID=33871897&style=metal&p=0\" />";
		echo "<object type=\"application/x-shockwave-flash\" data=\"http://grooveshark.com/songWidget.swf\" width=$width height=$height>";
		echo "<param name=wmode value=window />";
		echo "<param name=allowScriptAccess value=always />";
		echo "<param name=flashvars value=\"hostname=grooveshark.com&songID=33871897&style=metal&p=0\" /><span>";
		echo "<a href=$URL title=$title>$title</a></span></object></object>";	
		*/
	}
//	echo "<object width=$width height=$height <param name=flashvars value=\"hostname=grooveshark.com&style=metal&p=0\" /><span><a href=$URL>TITLE</a></span></object></object>";
//	echo "<iframe src=$URL width=$width height=$height frameborder=0 allowtransparency=true></iframe>";
//	echo "<object width=$width height=$height <param name=flashvars value=\"hostname=grooveshark.com&playlistID=77895142&p=0&bbg=000000&bth=000000&pfg=000000&lfg=000000&bt=FFFFFF&pbg=FFFFFF&pfgh=FFFFFF&si=FFFFFF&lbg=FFFFFF&lfgh=FFFFFF&sb=FFFFFF&bfg=666666&pbgh=666666&lbgh=666666&sbh=666666\" /><span></span></object></object>";
	echo "<a href=$URL target=newWin>Grooveshark</a>";
	if ($tableColumn) echo "</td>\n\t";
}

function ubdc_print_new_songs($debug, $bandID, $targetPHP, $PREVIOUS_ACTION, $style)
{
	// $debug = 1;
	if ($debug)	up_info( "ubdc_print_new_songs($debug, bID:'$bandID', tPHP:'$targetPHP', prevAction:'$PREVIOUS_ACTION'): ENTER");
	$debug = 0;

	if (!$targetPHP || $targetPHP == "ub_band_mgmt.php")
		$targetPHP = "ub_lyrics.php";
	$dbase = "bands";
	$table = "new_lyrics";
	$songStatus = 3; // Next rehersal
	$sql= "SELECT * FROM $dbase.$table, $dbase.lyrics_status WHERE $dbase.$table.uID = lyrics_status.songID AND lyrics_status.songStatus='$songStatus' AND $dbase.$table.bandID='$bandID' AND $dbase.lyrics_status.bandID='$bandID' ORDER BY title"; // $dbase.$table.uID, table2.column2
  	$result = mysql_query ($sql);	

	up_table_header();
	$tableColumn = 1;

	while ($row = mysql_fetch_array ($result))
	{
		if ($style == "DESKTOP"  || $style == "")
		{
			// Get the Spotify link... 
			$URL = ulin_get_spotify_URL($debug, $row[songID]);		

			if (strpos("$URL", "spotify") !== FALSE)
				ubdc_print_spotify_embedded($debug, $URL, 1);
			else if ($URL != "")
				ubdc_print_grooveshark_embedded($debug, $URL, 1);
			else	
				up_table_column("");
		}
		$title 		= $row[title];
		$artist		= $row[artist];
		// BEFORE : uly_print_lyrics_table_row($debug, $i, $targetPHP, $table, $title, $artist, $row[category], $row[uID], $row[theKey], $row[bandID]);
		// SONG shortcut
		ub_nice_link("$targetPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&bChords=1&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title,$target, $title, $tableColumn);
		// ARTIST (filter)
		ub_nice_link("$targetPHP?action=filter_lyrics&bandID=$bandID&artistFilter=$artist&bChords=1", $artist,$target, $artist, $tableColumn);
		// KEY
		uly_get_lyrics_data($debug, $dbase, $table, $row[songID], $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize, $NO_CONVERTION);
		if ($$originalKey != "")
			up_table_column("($originalKey)");
		else
			up_table_column("");
		// ==================
		// 		MP3
		// ==================
//		ulin_print_links($debug, "Lyrics", $row[songID], "mp3",  $targetPHP, $withAddButton, $maxLinks, $tableColumn, $align, $valign, $bgcolor, $height, $bandID);
		// ==================
		// 		PLAYBACKS
		// ==================
//		ulin_print_links($debug, "Lyrics", $row[songID], "playback",  $targetPHP, $withAddButton, $maxLinks, $tableColumn, $align, $valign, $bgcolor, $height, $bandID);
		up_new_table_row();
	}
	up_table_footer();
	// SPOTiFY PLAY LIST
//	if ($bandID == 1)
//		echo "<iframe src=https://embed.spotify.com/?uri=spotify:user:patnil:playlist:6amqx0Hj3alz0C1HJPd9fL width=250 height=380 frameborder=0 allowtransparency=true></iframe>";
}

// ============================================
//	START PAGE
// ============================================

function ubdc_print_start_page($debug, $bandID, $bandMemberID, $targetPHP, $stateFlag, $PREVIOUS_ACTION )
{
	// $debug = 1;
	if ($debug)	up_info( "ubdc_print_start_page($debug, bID:$bandID, bmID:$bandMemberID, targetPHP:$targetPHP, sFlag:$stateFlag, PREV_ACTION:$PREVIOUS_ACTION): ENTER");
	$debug = 0;
	
//	up_note("FIXME /ubdc_print_start_page/> Print band members, calender next gig/rehersal, upcoming events, new songs.");
	if ($bandMemberID == "")
	{
		$action = "print_add_bandmember_form";
		ubdc_print_nice_title($debug, "BAND MEMBERS", $targetPHP, $action, $bandID, "ADD");
	}
	else
	{
		$action = "edit_bandmember";
		$action = $action . "&userID=$bandMemberID";
		ubdc_print_nice_title($debug, "BAND MEMBER", $targetPHP, $action, $bandID, "EDIT");	
	}
	ubdc_print_band_members($debug, $bandID, $bandMemberID, $targetPHP, $stateFlag);
	ubdc_print_nice_title($debug, "UPCOMING EVENTS");
	ubdc_print_calendar($debug,  $bandID, 4);
	udbc_print_nice_gig_dates($debug,  $bandID, $targetPHP);
	ubdc_print_nice_title($debug, "NEW SONGS");
	ubdc_print_new_songs($debug, $bandID, $targetPHP, $PREVIOUS_ACTION);
}

function udbc_print_nice_gig_dates($debug,  $bandID, $targetPHP)
{
//	echo "udbc_print_nice_gig_dates($debug,  $bandID, $targetPHP): ENTER";
//	up_uls();
	echo "<center>\n";
	up_table_header();
	

	$today = date ("Y-m-d");
 	$query = "SELECT * FROM bands.gigs WHERE bandID='$bandID' AND date >= '$today' ORDER BY date , time";	
  	$result = mysql_query ($query);
 	while ($row = mysql_fetch_array ($result))
	{
		if($debug) echo "$row[date]";
		$monthChars = 20;
		uc_get_date_info($debug, $row[date], &$day, &$month, &$year, &$weekday, &$monthSwedish, $weekDayChar, $monthChars); 		
		$veckoDag = uc_get_swedish_weekday($row[date], $year, $month, $day, 8); // uc_get_swedish_weekday($weekDay);
		$niceTime = substr($row[time], 0, 5);
		//up_table_column("$veckoDag");
		up_table_column("$day $monthSwedish", "", "right");
		ubd_gi_get_arena_info($debug, $row[arenaID], &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL);
		//up_table_column("$city");
// ub_nice_link($URL, $label, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor)

		ub_nice_link("$targetPHP?action=view_gig&bandID=$bandID&gigID=$row[gigID]", $row[eventType], $target, "Visa detaljer", 1);
		up_table_column($row[city], "", "left");
	//	echo " - $row[city] - $row[city] - -$row[city]";
//		up_table_column("$row[eventType]", "", "left");
		up_table_column("$row[place]", "", "left");
		// up_table_column("$row[eventType]");
		up_new_table_row();
	}
	up_table_footer();
	echo "</center>\n";
//	up_ule();
}

// ============================================
//	GET BAND DATA/ DATABASE
// ============================================

function ubdc_get_band_data($debug, $level, $bandID, &$bandName, &$bandDB, &$bandLogo, &$menuLogo, &$iconLogo)
{
	$level = $level+1;

	if ($debug) up_note("ubdc_get_band_data($debug, $level, $bandID): ENTER - bandID:$bandID", $level);


  	$query = "SELECT * FROM bands.band WHERE bandID='$bandID'"; 
  	$result = mysql_query ($query);	

	while ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_note("Band found", $level);
		$bandName 	= $row[name];
		$bandDB 	= $row[bandDB];
		$bandLogo	= $row[logoImage];
		$menuLogo	= $row[menuImage];
		$iconLogo	= $row[iconImage];
	}
	if ($debug) up_note("ubdc_get_band_data: EXIT - $bandDB/$bandName", $level);
}	

function ubdc_get_band_db($debug, $level, $bandID)
{
	$level = $level+1;
	if ($debug) up_note("ubdc_get_band_db($debug, level:$level, bandID:$bandID): ENTER - bandID:<b>$bandID</b>", $level);

   	$query = "SELECT * FROM bands.band WHERE bandID='$bandID'"; 
  	$result = mysql_query ($query);	

	$bandDB = "";

	while ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_note("ubdc_get_band_db: database found $row[bandDB] $row[name] ", $level);
		$bandDB = $row[bandDB];
	}

	if ($debug) up_note("ubdc_get_band_db: EXIT - <b>$bandDB</b>", $level);
	return $bandDB;
}

function ubdc_get_firstname($userID)
{
 	$query = "SELECT * FROM bands.band_member WHERE id='$userID'"; 
  	$result = mysql_query ($query);

 	while ($row = mysql_fetch_array ($result))
  	{	
		return $row[firstName];
	}
	return "";
}

function ubdc_get_lastname($userID)
{
 	$query = "SELECT * FROM bands.band_member WHERE id='$userID'"; 
  	$result = mysql_query ($query);

 	while ($row = mysql_fetch_array ($result))
  	{	
		return $row[lastName];
	}
	return "";
}

// Aux : ubdc_get_firstname

function ubdc_get_singer($bandID, $songID)
{
	// up_error("FIXME: ubdc_get_singer($bandID, $songID): Table bands.singers is missing.");
	$query = "SELECT * FROM bands.singers WHERE bandID='$bandID' AND songID='$songID'";
 	$result = mysql_query ($query);
	// $debug = 1;
	//$row = mysql_fetch_array ($result);
	
	while ($row = mysql_fetch_array ($result))
	{
		$id = $row[userID];
		if($debug) up_note( " FOUND! < $row[userID] >");
	}
	
	if($debug) up_note( "ubdc_get_singer($bandID, $songID): returning id: '$id/$row[userID]'");

	if ($id == -2)
		return "Gäst";
	else
	{
	   $firstname = ubdc_get_firstname( $id );
	   if($debug) up_note( "firstname: $firstname");
 	   $initialLetter = substr($firstname, 0, 1);
	   $lastname = ubdc_get_lastname( $id );
	   if($debug) up_note( "lastname: $lastname");
 	   $initialLetterLN = substr($lastname, 0, 1);
	   return $initialLetter . "." . $initialLetterLN;
	}
}

// === get_nice_key_with_harmony($lyrics)
// Purpose : Given a lyric in a ChordsPro-format it returns its key.
//		To be used for printing the key in the lyric table.
//		Printing if it is a minor or not e.g. "Fm", "Abm".
// ====================================================================
function ubdc_get_nice_key_with_harmony($lyrics)
{
	if ($debug) up_note( "ubdc_get_nice_key_with_harmony($lyrics): ENTER", 3);
	$iFirstBracket  	= strpos($lyrics, "[");
	$strTheKey  		= $lyrics[$iFirstBracket+1];
	
	if($lyrics[$iFirstBracket+2] == '#' || $lyrics[$iFirstBracket+2] == 'b')
       {
		$strTheKey .= $lyrics[$iFirstBracket+2];
		if ($lyrics[$iFirstBracket+3] == 'm' )
		{
			$strTheKey .= $lyrics[$iFirstBracket+3];
		}

       }
	else if ($lyrics[$iFirstBracket+2] == 'm' )
	{
		$strTheKey .= $lyrics[$iFirstBracket+2];
	}
	return($strTheKey);
}

// Aux = ubdc_get_nice_key_with_harmony
function ubdc_get_key($debug, $bandID, $songID)
{
	if ($debug) up_note( "ubdc_get_key($debug, $bandID, $bandDB, $title, $artist): ENTER", 2);

  	$query = "SELECT * FROM bands.new_lyrics WHERE bandID='$bandID' AND songID='$songID'"; //   WHERE artist = '$artist'";
	if ($debug) up_note($query);
  	$result = mysql_query ($query);
  	while ($row = mysql_fetch_array ($result))
  	{
		$theKey =  ubdc_get_nice_key_with_harmony($row[text]);
	    mysql_free_result ($result);

	    return $theKey;
  	}
  	mysql_free_result ($result);
  	return "ubdc_get_key: key not found";
}

function ubdc_get_short_title($debug, $title, $maxChars)
{
	if ($maxChars == "")   $maxChars = 18;
	$returnString = $title;
	if ($debug) up_note( "ubdc_get_short_title($debug, $title, $maxChars): ENTER", 2);
	if (strlen($title) >= $maxChars)
	{
		$tempString = substr($title, 0, $maxChars);
		// Find last whitespace of the title
		$iPos = strrpos($tempString, " ");
		if ($iPos < $maxChars && $iPos != FALSE)
		{		
			$returnString = substr($tempString, 0, $iPos);
			$returnString = $returnString . "...";
		}	
	}
	else
	{
		if ($debug)  echo " - Title is less than $maxChars characters";
	}
	if ($debug) up_note ( "ubdc_get_short_title($debug, $title, $maxChars): EXIT = $returnString", 2);
	return $returnString;
}

// Aux. functions ubdc_get_calendar
function ubdc_print_calendar($debug, $bandID, $nofMonths)
{
// IGNORE WARNING>> It is not possible to use the COMMON since it depends on BAND rehersals And GIGS
//	up_warning("FIXME: util_band_common/ubdc_print_calendar: include util_calender instead of dual code.");
	if ($debug) up_note("ubdc_print_calendar($debug, bID:$bandID, #months:$nofMonths) : ENTER");
	$date 	= getDate();
	$month 	= $date["mon"];
	$year 	= $date["year"];
	$firstMonth = $month;
	if($nofMonths > 12)
	{
		up_error( "ubdc_print_calendar : Max number of months 12 will be displayed.");
		$nofMonths = 12;
	}
	// echo "<p><table><tr><th valign=top>$calendar1</th><th valign=top>$calendar2</th><th valign=top>$calendar3</th></tr></table>";
	up_table_header();
	for($i=1;$i<=$nofMonths;$i++)
	{
		$calendar[$i] = ubdc_get_calendar($debug, $bandID, "", $year, $month); 
		//$calendar[$i] = uc_draw_calendar($month, $year);
		$month = ($month%12)+1; 
		if ($month < $firstMonth && !$done)
		{
	  		$year++; $done=1;
	  	}
	  	echo "<th valign=top>$calendar[$i]</th>\n";
	}
	up_table_footer();
	if ($debug) up_note("ubdc_print_calendar($debug, $bandID, $nofMonths) : EXIT");
}

// ================================================================================
//  SCHEDULE related functions
// ================================================================================

function ubdc_month_in_swedish_non_capital($month)
{

}

function ubdc_weekday_in_swedish($weekDay)
{

}
// ==== ubdc_GetSwedishMonth/1
// Purpose	: 
// Input 	: i'th month [0, 11]
// Output	: [Januari, December]
// ==============================
function ubdc_GetSwedishMonth($iThMonth)
{
	switch($iThMonth)
	{
	case 0: $monthName = "Januari"; break;
	case 1: $monthName = "Februari"; break;
	case 2: $monthName = "Mars"; break;
	case 3: $monthName = "April"; break;
	case 4: $monthName = "Maj"; break;
	case 5: $monthName = "Juni"; break;
	case 6: $monthName = "Juli"; break;
	case 7: $monthName = "Augusti"; break;
	case 8: $monthName = "September"; break;
	case 9: $monthName = "Oktober"; break;
	case 10: $monthName = "November"; break;
	case 11: $monthName = "December"; break;
	default: $monthName = "Ogiltig mÂnad: $iThMonth";
	}
	return $monthName;
}

function ubdc_HasDatePassed($year, $month, $day_counter)
{
	 $adate = date("Y-m-d");
	
	if ($day_counter < 10)
		$day_counter = "0" . $day_counter;

	if ($month >= 10)
	   $givenDate = $year . "-" . $month . "-" . $day_counter;
	else
	   $givenDate = $year . "-0" . $month . "-" . $day_counter;

	if ($adate > $givenDate)
	{
	   return TRUE;
	}
	else
	{
	   return FALSE;
	}
}

function ubdc_isPublicHoliday($year, $month, $day)
{
	if($debug) up_note( "ubdc_isPublicHoliday($year, $month, $day): inside $i<br>") ; 
	//echo "ubdc_isPublicHoliday($year, $month, $day)<br>\n";
	$TheDate = "$year-$month-$day";
   	$query = "SELECT * FROM publicHolidays WHERE date='$TheDate'"; 
  	$result = mysql_query ($query);

	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		//echo "row[date] = $row[date]<br>";
		$i++;
	}
	if($debug) up_note( "ubdc_isPublicHoliday($year, $month, $day): leaving $i<br>") ; 
	return $i;
}

function ubdc_isRehersalDate($debug, $bandID, $year, $month, $day)
{
	if ($bandID == "" || $year== "" || $month== "" || $day== "")
	{
		up_error("ubdc_isRehersalDate($debug, $bandID, $year, $month, $day): Invalid NULL parameters.");
		return;
	}	
	if($debug) up_note( "ubdc_isRehersalDate($debug, $bandID, $year, $month, $day): inside - FIXME+CHANGE PARAMETER from DB to ID<br>") ;
	$TheDate = "$year-$month-$day";
   	$query = "SELECT * FROM bands.rehersals WHERE date='$TheDate' AND bandID='$bandID'"; 
  	$result = mysql_query ($query);

	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		$i++;
	}
	if($debug) up_note( "ubdc_isRehersalDate($year, $month, $day): leaving $i<br>") ;
	return $i;
}

function ubdc_isGigDate($debug, $bandID, $year, $month, $day)
{
	//$debug = 1;
	if($debug) up_note( "ubdc_isGigDate($debug, $bandID, $year, $month, $day): inside $i<br>") ; 
	if ($month < 10)
		$month = "0" . $month;
	if ($day < 10)
		$day = "0" . $day;
	$TheDate = "$year-$month-$day";
   	$query = "SELECT * FROM bands.gigs WHERE date='$TheDate' AND bandID='$bandID'"; 
  	$result = mysql_query ($query);

	if($debug)  up_note( "$query");
	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		$i++;
	}
//	if ($i > 0)
//		up_warning ("FOUND a GIG");
	if($debug) up_note( "ubdc_isGigDate($debug, $bandID, $year, $month, $day): EXIT $i<br>") ; 
	return $i;
}

function ubdc_GetGigAddress($debug, $bandID, $year, $month, $day)
{
	if($debug) up_note( "ubdc_GetGigAddress($debug, $bandID, $year, $month, $day): inside $i<br>") ; $i++;
	$TheDate = "$year-$month-$day";
   	$query = "SELECT * FROM bands.gigs WHERE gigDate='$TheDate' AND bandID='$bandID'"; 
  	$result = mysql_query ($query);

	$address = "";
	while ($row = mysql_fetch_array ($result))
	{
		$niceTime = substr($row[gigTime], 0, 5);

		if ($row[gigAddress] != "TBD")
		  $address .= $row[eventType] . "@" . $row[gigAddress] . " kl." . $niceTime;		
		else
		  $address .= $row[eventType] . "@" . $row[gigCity] . " kl." . $niceTime;		
	}	
	if($debug) up_note( "ubdc_GetGigAddress($debug, $bandID, $year, $month, $day): EXIT $i<br>") ; $i++;
	return $address;
}

// Uses
//		ubdc_isGigDate
// 		ubdc_isRehersalDate
//		ubdc_GetSwedishMonth
//		ubdc_isPublicHoliday
//		ubdc_HasDatePassed
//		ubdc_GetGigAddress
function ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)
{
	if ($bandID == "" || $Year== "" || $Month== "" ) // || $date== "" COULD BE NULL
	{
		up_error("ubdc_get_calendar($debug, $bandID, $date, $Year, $Month): Invalid NULL parameters.");
		return;
	}	
	if($debug) 
		up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): inside $i<br>") ; $i++;
    //If no parameter is passed use the current date.
	if($date == null)
        $date = getDate();

	if($debug) up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): inside $i<br>") ; $i++;
	$gigColor = "55ff55";		// GREEN
	$rehersalColor = "ffff55";	// YELLOW
	$saturdayColor = "eeeeee";
	$sundayColor = "ffaaaa";	// RED
	$weekdayColor = "ffffff";

    $day = $date["mday"];
    $thisRealmonth = $date["mon"];

	if($debug) up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): inside $i<br>") ; $i++;
	if ($Month == "")
            $month = $date["mon"];
	else
            $month = $Month;
	$month_name = ubdc_GetSwedishMonth($month-1);
	if ($Year == "")
            $year = $date["year"];
	else
            $year = $Year;

  	if($debug) up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): inside $i<br>") ; $i++;
  $this_month = getDate(mktime(0, 0, 0, $month, 1, $year));
    $next_month = getDate(mktime(0, 0, 0, $month + 1, 1, $year));

    //Find out when this month starts and ends.
    $first_week_day = $this_month["wday"]; // Subtract one to start on a monday!
	if($first_week_day == 0)
		$first_week_day = 6;
    else
		$first_week_day--;
	  //echo "first_week_day = $first_week_day<br>";
    $days_in_this_month = round(($next_month[0] - $this_month[0]) / (60 * 60 * 24));

	  //echo "days_in_this_month = $days_in_this_month<br>";
    $calendar_html = "<table style=\"background-color:#ffffff; color:ffffff;\">";

	  // MÅNADSFÄRG + TEXT
    $calendar_html .= "<tr><td  class=\"calendar-header\" colspan=\"7\" align=\"center\" style=\"background-color:#$saturdayColor; color:000000;\">" . $month_name . " " . $year . "</td></tr>";

    $calendar_html .= "<tr>";

    //Fill the first week of the month with the appropriate number of blanks.
    for($week_day = 0; $week_day < $first_week_day; $week_day++)
    {
        $calendar_html .= "<td class=\"calendar-blank_weekday\"  style=\"background-color:#ffffff color:000000;\"> </td>";
    }

    $week_day = $first_week_day;
 	if($debug) up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): inside $i<br>") ; $i++;
   for($day_counter = 1; $day_counter <= $days_in_this_month; $day_counter++)
    {
        $week_day %= 7;

        if($week_day == 0)
            $calendar_html .= "</tr><tr>";

        //  Do something different for the current day.
        if($day == $day_counter && ($Month == "" || $month == $thisRealmonth))
	    {
 			if($debug) up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): TODAY") ; $i++;
			// TODAY
	      	if ( ubdc_isGigDate($debug, $bandID, $year, $month, $day_counter))
	      	{
               	$calendar_html .= "<td class=\"calendar-gig_day\"  align=\"center\" style=\"background-color:#$gigColor; color:ffffff;\"><b>&nbsp;";
		 		$calendar_html .=  $day_counter . " </b></font></td>";
	      	}
	      	else if ( ubdc_isRehersalDate($debug, $bandID, $year, $month, $day_counter))
	      	{
               	$calendar_html .= "<td  class=\"calendar-rehersal_day\" align=\"center\" style=\"background-color:#$rehersalColor; color:000000;\"><b>&nbsp;" .
                	$day_counter . " </b></td>";
	      	}
	      else if ( ubdc_isPublicHoliday($year, $month, $day_counter))
	      {
		  		if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
                  	$calendar_html .= "<td  class=\"calendar-sunday\" align=\"center\" style=\"background-color:#$sundayColor; color:000000;\">&nbsp;" . $day_counter . "</td>";
		  		else	
                 	$calendar_html .= "<td class=\"calendar-sunday\" align=\"center\" style=\"background-color:#$sundayColor; color:000000;\">&nbsp;<strike>" . $day_counter . " </strike></td>";
	      }
	      else
	      {
		  		if (ubdc_HasDatePassed($year, $month, $day_counter))
                  $calendar_html .= "<td class=\"calendar-weekday\" align=\"center\" style=\"background-color:#$weekdayColor; color:000000;\" ><b><u><str>" . $day_counter . "</str></b></u></td>";
		  		else
                   $calendar_html .= "<td class=\"calendar-weekday\" align=\"center\" style=\"background-color:#$weekdayColor; color:000000;\" ><b><u>" . $day_counter . "</b></u></td>";
          }
		}
        else // NOT CURRENT DATE
	    {
			if($debug) up_note( "ubdc_get_calendar($debug, $bandID, $date, $Year, $Month)): NOT TODAY") ; $i++;
	      	if ( ubdc_isGigDate($debug, $bandID, $year, $month, $day_counter))
	      	{	
		 		$gigAddress = ubdc_GetGigAddress($debug, $bandID, $year, $month, $day_counter);
		 		$TheDate = "$year-$month-$day_counter";

  		  		if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
                 	$calendar_html .= "<td class=\"calendar-gig_day\" align=\"center\" style=\"background-color:#$gigColor; color:ffffff;\">&nbsp; <font color=ffffff><a href=\"ub_band_mgmt.php?action=view_gig&date=$TheDate&bandID=$bandID\" onmouseover=\"doalt('$gigAddress')\" onmouseout=\"realt()\"<b>" . $day_counter . " </b></font></a></td>";
  		  		else
                 	$calendar_html .= "<td class=\"calendar-gig_day\" align=\"center\" style=\"background-color:#$gigColor; color:ffffff;\">&nbsp; <font color=ffffff><a href=\"ub_band_mgmt.php?action=view_gig&date=$TheDate&bandID=$bandID\" onmouseover=\"doalt('$gigAddress')\" onmouseout=\"realt()\"<b><strike>" . $day_counter . " </strike></b></font></a></td>";
	      	}
	      	else if ( ubdc_isRehersalDate($debug, $bandID, $year, $month, $day_counter))
	      	{
		  		if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
  		     		$calendar_html .= "<td align=\"center\" style=\"background-color:#$rehersalColor; color:000000;\">&nbsp;" . $day_counter . " </td>";
		  		else
  		     		$calendar_html .= "<td align=\"center\" style=\"background-color:#$rehersalColor; color:000000;\">&nbsp;<strike>" . $day_counter . " </strike></td>";
	      	}
	      	else if ( ubdc_isPublicHoliday($year, $month, $day_counter))
	      	{
		  		if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
                  	$calendar_html .= "<td class=\"calendar-sunday\" align=\"center\" style=\"background-color:#$sundayColor; color:000000;\">&nbsp;" . $day_counter . "</td>";
		  		else	
                 	$calendar_html .= "<td class=\"calendar-sunday\" align=\"center\" style=\"background-color:#$sundayColor; color:000000;\">&nbsp;<strike>" . $day_counter . " </strike></td>";
	      	}
          	else
	      	{
				if($debug) up_note ("$year-$month-$day_counter - not gig, rehersal or holiday", 2);
               	if($week_day < 5) //  ie. mon-fri
		 		{
		  			if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
                  		$calendar_html .= "<td class=\"calendar-weekday\" align=\"center\" style=\"background-color:#$weekdayColor; color:000000;\">&nbsp;" . $day_counter . " </td>";
		  			else
                  		$calendar_html .= "<td class=\"calendar-weekday\" align=\"center\" style=\"background-color:#$weekdayColor; color:000000;\">&nbsp;<strike>" . $day_counter . " </strike></td>";
		 		}
		 		else if ($week_day == 5) // ie. saturday
		 		{
		  			if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
                  		$calendar_html .= "<td class=\"calendar-saturday\" align=\"center\" style=\"background-color:#$saturdayColor; color:000000;\">&nbsp;" . $day_counter . " </td>";
		  			else
                  		$calendar_html .= "<td class=\"calendar-saturday\" align=\"center\" style=\"background-color:#$saturdayColor; color:000000;\">&nbsp;<strike>" . $day_counter . " </strike></td>";
		 		}
 		 		else if ($week_day == 6) // ie. sunday
		 		{
		  			if (!ubdc_HasDatePassed($year, $month, $day_counter))		   
                  		$calendar_html .= "<td class=\"calendar-sunday\" align=\"center\" style=\"background-color:#$sundayColor; color:000000;\">&nbsp;" . $day_counter . "</td>";
		  			else	
                 		$calendar_html .= "<td class=\"calendar-sunday\" align=\"center\" style=\"background-color:#$sundayColor; color:000000;\">&nbsp;<strike>" . $day_counter . " </strike></td>";
		 		}
	      	}
        } // end else NOT CURRENT DATE

    	$week_day++;
	    // echo "week_day = $week_day - day_counter = $day_counter<br>";
    }  // End -    for($day_counter = 1; $day_counter <= $days_in_this_month; $day_counter++)
    $calendar_html .= "</table>";
	if($debug) up_note( "print_calendar($date): leaving<br>");
    return($calendar_html);
}

// ================================================================================
//  SÖKNING
// ================================================================================

function ubdc_search ($debug, $bandID, $targetPHP, $searchItem)
{
	echo "<h3>Låtar</h3>";
	$dbase = "bands";
	$table = "new_lyrics";
	$ly_targetPHP =" ub_lyrics.php";
	if (ubd_count_nof_lyrics($debug, $bandID, $searchItem, $artistFilter, $dbase, $table) > 0)
	{
		uly_print_lyrics_table($debug, $dbase, $table, $ly_targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $searchItem, $artistFilter, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID );
	}
	else
	{
		echo "Inga låtmatchningar för <b>$searchItem</b>.";
	}
	//uly_print_title_filter_rows($debug, $dbase, $table, $searchItem);
	up_table_header();
	up_table_footer();
	echo "<h3>Artister</h3>";
	if (ubd_count_nof_lyrics($debug, $bandID, $titleFilter, $searchItem, $dbase, $table) > 0)
	{
		//up_table_header();
		uly_print_lyrics_table($debug, $dbase, $table, $ly_targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $searchItem, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID );
		//up_table_footer();
	}
	else
	{
		echo "Inga artistmatchningar för <b>$searchItem</b>.";
	}	
/*
	echo "<h3>Rep</h3>";
	echo "<h3>Spelningar</h3>";
	echo "<h3>Låtlistor</h3>";
	echo "Inga";
*/
	echo "<h3>Att importera...</h3>";
	$MainDb = "Lyrics";
	if (ubd_count_nof_lyrics($debug, $bandID, $searchItem, $artistFilter, $MainDb, $tableName) > 0)
	{
		echo "<h3>Låttitlar att importera...</h3>";
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $searchItem, $artistFilter, $instrumentFilter, $genreFilter, $bandID, 1 );
	}
	else
	{
		echo "Inga låtmatchningar för <b>$searchItem</b> i huvuddatabasen.<br>";		
	}
	if (ubd_count_nof_lyrics($debug, $bandID, $titleFilter, $searchItem, $MainDb, $tableName) > 0)
	{
		echo "<h3>Artister att importera...</h3>";
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $searchItem, $instrumentFilter, $genreFilter, $bandID, 1 );
	}
	else
	{
		echo "Inga artistmatchningar för <b>$searchItem</b> i huvuddatabasen.<br>";		
	}
}

function ubdc_search_responsive ($debug, $bandID, $targetPHP, $searchItem)
{
	ucssr_print_header_title("Låtar");
	$dbase = "bands";
	$table = "new_lyrics";
	$ly_targetPHP =" ub_lyrics.php";
	if (ubd_count_nof_lyrics($debug, $bandID, $searchItem, "", $dbase, $table) > 0)
	{
		ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $table, $targetPHP, "uID", $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $songStatus, $searchItem, $artistFilter, $selectedInstrument, $bandID, $highLightSongID, $PREVIOUS_ACTION);
	//	uly_print_lyrics_table($debug, $dbase, $table, $ly_targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $searchItem, $artistFilter, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID );
	 	// ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $tableName, $targetPHP, "uID", "", $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, 3, $titleFilter, $artistFilter, $instrument, $bandID, $highlightID, $PREVIOUS_ACTION);
	}
	else
	{
		echo "Ingen match för '<b>$searchItem</b>'.";
	}
	//uly_print_lyrics_table($debug, $dbase, $table, $ly_targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $searchItem, $artistFilter, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID );
	ucssr_print_header_title("Artister");

	if (ubd_count_nof_lyrics($debug, $bandID, "", $searchItem, $dbase, $table) > 0)
	{
		ulyr_print_lyrics_table_by_lyrics_status($debug, $dbase, $table, $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $songStatus, $titleFilter, $searchItem, $selectedInstrument, $bandID, $highLightSongID, $PREVIOUS_ACTION);
	}
	else
	{
		echo "Ingen match för '<b>$searchItem</b>'.";
	}
//	ulyr_print_lyrics_table($debug, $dbase, $table, $ly_targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $searchItem, $instrumentFilter, $bandID, $importFlag, $anchorPoint, $highLightSongID );

	
/*
	ucssr_print_header_title("Rep");
	ucssr_print_header_title("Spelningar");
	ucssr_print_header_title("Låtlistor");
	ucss_section_start($debug, "section2", "clearfix");
	echo "Inga";
	ucss_section_end($debug, "section2", "clearfix");
*/
	ucssr_print_header_title("Att importera...");
	if (ubd_count_nof_lyrics($debug, $bandID, $searchItem, "", $MainDb, $tableName) > 0)
	{	
		// TITLE
		ucss_section_start($debug, "section2", "clearfix");
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $searchItem, $artistFilter, $instrumentFilter, $genreFilter, $bandID, 1 );
		ucss_section_end($debug, "section2", "clearfix");
	}
	if (ubd_count_nof_lyrics($debug, $bandID, "", $searchItem,  $MainDb, $tableName) > 0)
	{	
		// TITLE
		ucss_section_start($debug, "section2", "clearfix");
		uly_print_import_table($debug, $MainDb, $tableName , $targetPHP, $uniqueKeyLabelName, $stateFlag, $edituID, $givenTitle, $givenArtist, $givenCategory, $givenLanguage, $titleFilter, $searchItem, $instrumentFilter, $genreFilter, $bandID, 1 );
		ucss_section_end($debug, "section2", $class);
	}
}

?>