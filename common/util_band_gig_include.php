<?php

function ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID, &$hotelBookingReference)
{
	if ($debug) up_note("ubd_gi_get_data($debug, $bandID, gigID:$gigID, $date, $time, $notes, $location): ENTER");
	$sql = "SELECT * FROM bands.gigs WHERE bandID='$bandID' AND gigID='$gigID'";
  	$result = mysql_query ($sql);
	while ($row = mysql_fetch_array ($result))
	{
		$eventType 				= $row[eventType];
		$date 					= $row[date];
		$time 					= $row[time];
		$eventType 				= $row[eventType];
		$arenaID 				= $row[arenaID];
		$repertoirID 			= $row[repertoirID];
		$hotelBookingReference 	= $row[hotelBookingReference];
	}
	if ($debug) up_note("ubd_gi_get_data($debug, $bandID, gigID:$gigID, date:$date, time:$time, notes:$notes, location:$location, aID:$arenaID, rID:$repertoirID, hbRef:$hotelBookingReference): EXIT");
}

function ubd_gi_get_detailed_data($debug, $gigID, &$accessTime, &$gatheringTime, &$departureTime, &$unpackTime, &$soundCheckTime, &$soundCheckEndTime, &$onStageTime, &$gatheringDate, &$departureDate, &$unpackDate, &$soundCheckDate)
{
	// $debug = 1;
	if ($debug) up_note("ubd_gi_get_detailed_data($debug, gigID:$gigID): ENTER");
	$sql = "SELECT * FROM bands.gig_details WHERE gigID='$gigID'";
  	$result = mysql_query ($sql);
	while ($row = mysql_fetch_array ($result))
	{
		$accessTime 		= substr($row[accessTime], 0, 5);
		$gatheringTime 		= substr($row[gatheringTime], 0, 5);
		$departureTime 		= substr($row[departureTime], 0, 5);
		$unpackTime 		= substr($row[unpackTime], 0, 5);
		$soundCheckTime 	= substr($row[soundCheckTime], 0, 5);
		$soundCheckEndTime 	= substr($row[soundCheckEndTime], 0, 5);
		$onStageTime 		= substr($row[onStageTime], 0, 5);
		$gatheringDate		= $row[gatheringDate];
		$departureDate		= $row[departureDate];
		$unpackDate			= $row[unpackDate]; 
		$soundCheckDate		= $row[soundCheckDate];
	}
	if ($debug) up_note("ubd_gi_get_detailed_data($debug, gigID:$gigID, , $accessTime, $gatheringTime, $departureTime, $unpackTime, $soundCheckTime, $soundCheckEndTime, $onStageTime): EXIT");

}

function ubd_gi_print_gigs_option_menu($debug, $bandID, $tableColumn)
{
	$today = date("Y-m-d");
	$sql = "SELECT * FROM bands.gigs WHERE bandID='$bandID' AND date <= '$today' ORDER BY date DESC";
	
	$name = "gigID";
	up_select_header($debug, $targetPhp, $name, $identity, $callItself, $onChangeFunctionName, $tableColumn, $colSpan, $bgcolor, $tableAlign);
	
  	$result = mysql_query ($sql);
	while ($row = mysql_fetch_array ($result))
	{
		$label = "$row[date] - $row[eventType]";
		$value = $row[gigID];
		up_select_menu_item($debug, $label, $value, $disabled, $selectedValue);
	}	
	up_select_footer($debug, $tableColumn, $callItself);
}

// ============================================
//	ARENA INFO
// ============================================

function ubd_gi_get_arena_info($debug, $arenaID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL)
{
	if ($debug) up_note("ubd_gi_get_arena_info($debug, $bandID, gigID:$gigID, $date, $time, $notes, $location): ENTER");
	$sql = "SELECT * FROM bands.arena WHERE uID='$arenaID'";
  	$result = mysql_query ($sql);
	while ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_note("Arena Found!");
		$name = $row[name];
		$streetAddress = $row[streetAddress];
		$city = $row[city];
		$postalCode = $row[postalCode];
		$phoneNumber = $row[phoneNumber];
		$URL = $row[URL];
		return;
	}	
		$name = "";
		$streetAddress = "";
		$city = "";
		$postalCode = "";
		$phoneNumber = "";
		$URL = "";	
	
}

function ubd_gi_calc_future_gigs($debug, $bandID)
{
	$NofRehersals = 0;
	$today = date("Y-m-d");
	$sql = "SELECT COUNT(bandID) AS NofGigs FROM bands.gigs WHERE date>='$today' AND bandID='$bandID'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array ($result);
   
    $NofGigs = $row[NofGigs];	
	if($debug) up_note( "ubd_gi_calc_future_gigs: $NofGigs = $sql");
	return $NofGigs;
}

function ubd_gi_exist($debug, $bandID, $date, $time)
{
	if ($debug) up_note("ubd_gi_exist: ENTER");
	$sql ="SELECT * FROM bands.gigs WHERE bandID='$bandID' AND date='$date' AND time='$time'";
	$result = mysql_query($sql);
	while(($row = mysql_fetch_array ($result)) != NULL)
	{
		return TRUE;		
	}
	return FALSE;	
}

function ubd_gi_is_gig_date($year, $month, $day, $bandID)
{
	// $debug = 1;
	$TheDate = "$year-$month-$day";
   	$query = "SELECT * FROM bands.gigs WHERE date='$TheDate' AND bandID=$bandID"; 
  	$result = mysql_query ($query);

	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_info ("$TheDate is a gig date for band $bandID");
		$returnValue = $row[gigID];
		$i++;
	}
	return $returnValue; // $i;
}
// ============================================
//	PRINT EDIT and ADD FORM - GIG
// ============================================
// 	ubd_gi_print_form($debug, $bandID, 0, $gigID, $date, $time, $notes, $arenaID, $bandMgmtPHP, "ADD");

function ubd_gi_print_form($debug, $bandID, $edit, $gigID, $date, $time, $notes, $arenaID, $bandMgmtPHP, $stateFlag)
{
	if ($edit && $gigID == "")
	{
		$edit = 0;
	}
	else
	{
		if ($debug)
			up_info("ubd_gi_print_form(dbug:$debug, bID:$bandID, edit:$edit, gigID:$gigID, $date, $time, $notes, $arenaID, $bandMgmtPHP, $stateFlag)");
	}
	if ($debug) up_info ("Stateflag: '$stateFlag'");
	if ($stateFlag == "INVALID_PARAMETERS")
		$errorIndication = 1;
	$level = 0;
	if ($bandID == ""  || $bandMgmtPHP == "" || $stateFlag  == "")
	{
		up_error("ubd_gi_print_form(dbug:'$debug', bID:'$bandID', edit:'$edit', gID:'$gigID', bPHP:'$bandMgmtPHP', sFlag:'$stateFlag'): Invalid NULL parameters");
		return;
	}
	if ($edit)
		ubdc_get_band_data($debug, $level, $bandID, $bandName, $bandDB);

	if ($debug) up_note("ubd_gi_print_form: ENTER - EDIT=$edit / $bandID=$bandName/$bandDB", $level);

	if ($debug) up_note("ubd_gi_print_form($debug, bID:$bandID,  edit:$edit, gID:$gigID, $date, $time, $notes, $location): ENTER");
	if($debug)
		ubdc_print_calendar(!$debug,  $bandID, 4);
	else
		ubdc_print_calendar($debug,  $bandID, 4);

	if ($edit)
	{
		//$debug = 1;
		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$old_arenaID, &$repertoirID);
		if($debug)  "ubd_gi_get_data($debug, $bandID,  $gigID, $date, $time, $eventType, $arenaID);";
		$debug = 0;
	}
	echo "<ul>";
	up_table_header(0);
	$year = date("Y");
	$month = date("m");
	$tableColumn = 1;
	$valign = "baseline";
	$align 	= "right";
	// =============================
	// ARENA
	// =============================
	up_new_table_row();
	up_table_column("Plats:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);

	if ($arenaID == "")
		$arenaID = $old_arenaID;
	ubdc_print_arena_option_menu_form($debug, $bandID, $bandMgmtPHP, $arenaID, 1, $disabled, $stateFlag, $givenTitle, $givenArtist, $givenCategory, $givenarena, $gigID);

	if ( $stateFlag == "PREPARE_ADD")
	{
		return;
		$disabled = 0;
		$bgcolor = "#aaaaaa";
		$readOnly = TRUE;
		 $noBorder = 1;
	}
	$valign = "center";
	// =============================
	// FORM GIG
	// =============================
	up_form_header($bandMgmtPHP);
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("uID", $gigID, "hidden");
	up_php_param("arenaID", $arenaID, "hidden");
	if (!$edit || $gigID == "" )
	 	up_php_param("action", "add_gig", "hidden");
	else
	{
	 	up_php_param("action", "update_gig", "hidden");
		up_php_param("gigID", $gigID, "hidden");
		up_php_param("arenaID", $arenaID, "hidden");
	 }
	// ========================================
	// 		DATE
	// ========================================
	up_new_table_row();
	up_table_column("Datum:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	if ($date == "")
		up_php_param("date", "$year-$month-", "", 1, 10, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	else if (($ecode = uc_is_valid_date($debug, $date)) < 0)
	{
		up_php_param("date", "$date", "", 1, 10, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
		switch ($ecode)
		{
		case -1: up_table_column("Ogiltigt datum format (YYYY-mm-dd)", 0, $align, $size, $valign); break;
		case -2: up_table_column("Ogiltig månad (01-12)", 0, $align, $size, $valign); break;
		case -3: up_table_column("Ogiltigt dag (01-31)", 0, $align, $size, $valign); break;
		}
	}
	else // No error indication
		up_php_param("date", "$date", "", 1, 10, 0, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	// =============================
	// 		TIME
	// =============================
	up_new_table_row();
	if ($time == "") $time = "18:00";
	up_table_column("Tid:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	if (uc_is_valid_time($debug, $time) < 0)
	{
		up_php_param("time", $time, "", 1, 6, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	}
	else  // No error indication
	{
		up_php_param("time", $time, "", 1, 6, "", $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked);
	
	}
	//	echo "<th align=right>Tid: </th><td><input size=5 name=time value=$time> </td>\n\t";
	up_new_table_row();
	up_table_column("Evenemang:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_php_param("eventType", $eventType, "", 1, 50, "", $readOnly, $textalign, $noBorder, $onChange, 5, $tableAlign, $fontSizePx, $unit, $bgcolor, 80, $tableValign, $radioChecked);
	up_new_table_row();
	up_table_column("Låtlista:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	ubd_rp_print_repertoir_option_menu($debug, $bandID, $repertoirID, $disabled, $tableColumn);
	up_new_table_row();
	up_table_column("");
	if ($stateFlag == "EDIT" && $uID != "")
	{
		echo "<td>";
		$tCol  = 0;
		ub_save("", 20, "", "Uppdatera spelning...", $tCol);
		ub_cancel("$bandMgmtPHP?action=view_gigs&bandID=$bandID", 20, "", "Avbryt", $tCol);
		echo "</td>";
	}
	else if ( $stateFlag != "PREPARE_ADD" && $stateFlag != "ERROR")
	{
		echo "<td>";
		$tCol  = 0;
		ub_save("", 20, "", "Lägg till spelning...", $tCol);
		ub_cancel("$bandMgmtPHP?action=view_gigs&bandID=$bandID", 20, "", "Avbryt", $tCol);
		echo "</td>";
	}

	//ubdc_print_arena_option_menu($debug, $selectedPlace, 1);
	up_form_footer();
	up_table_footer();
	echo "</ul>";
	if ($debug) up_note("ubd_gi_print_form: EXIT", $level);
}

// ============================================
//	VIEW - GIG
// ============================================

// PRINTs ALL UPCOMING GIGS
function ubd_gi_view($debug, $bandID, $gigID, $bandMgmtPHP, $date) // If gigID == "" => View All
{
	$level = 1;
	ubdc_get_band_data($debug, $level, $bandID, $bandName, $bandDB);

	if ($debug) up_note("ubd_gi_view($debug, bID:$bandID, gID:$gigID): ENTER", $level);

	$today = date ("Y-m-d");
	if ($date == "")
 		$query = "SELECT * FROM bands.gigs WHERE bandID='$bandID' AND date >= '$today' ORDER BY date, time";	
 	else
 		$query = "SELECT * FROM bands.gigs WHERE bandID='$bandID' AND date = '$date' ORDER BY time";	
  if($debug) up_note ("sql=$query");
  	$result = mysql_query ($query);
	
	//$debug = 0;
	if ($debug)
		ubdc_print_calendar(!$debug,  $bandID, 4);
	else
		ubdc_print_calendar($debug,  $bandID, 4);
	//$debug = 1;

	if (ubd_gi_calc_future_gigs($debug, $bandID) == 0)
	{
		up_uls();
		echo "<font size=4>Inga planerade spelningar.</font>\n";
		up_ule();
		return;
	}
	//PrintThreeMonths();
	echo "<p><ul><table border=0>\n";
	echo "<tr><th colspan=2></th><th align=left colspan=4 width=180>Datum</th><th align=left colspan=1>Evenemang</th><th align=left>Plats</th><th align=left>Stad</th></tr>";
	$tableColumn = 1;
	$done = FALSE;
	while ($row = mysql_fetch_array ($result))
	{
		if($debug) echo "$row[date]";
		$monthChars = 3;
		uc_get_date_info($debug, $row[date], &$day, &$month, &$year, &$weekday, &$monthSwedish, $weekDayChar, $monthChars); 		
		$veckoDag = uc_get_swedish_weekday($row[date], $year, $month, $day, 8); // uc_get_swedish_weekday($weekDay);
		
		$niceTime = substr($row[time], 0, 5);

		up_new_table_row();
//$URL, $height, $target, $title, $tableColumn
		ub_delete("$bandMgmtPHP?action=delete_gig&date=$row[date]&bandID=$bandID&gigID=$row[gigID]", 20, "", "Ta bort spelning", 1);
		ub_edit("$bandMgmtPHP?action=edit_gig&date=$row[date]&bandID=$bandID&gigID=$row[gigID]", 20, "", "Redigera spelning", 1);
		// up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)
		up_table_column($weekday, $bold, "right");
		up_table_column($day, $bold, "right");
		up_table_column($monthSwedish);
		up_table_column("kl.$niceTime");
		// EVENEMANG
		ub_nice_link("$bandMgmtPHP?action=view_gig&bandID=$row[bandID]&gigID=$row[gigID]", $row[eventType], $target, $row[eventType], $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor);
		
		//up_table_column($row[eventType]);
		// === ARENA INFO === 
		// $debug = 1;
		ubd_gi_get_arena_info($debug, $row[arenaID], &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL);
		if ($URL != "")
			ub_nice_link($URL, $name, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor);
		else
			up_table_column($name);
		up_table_column($city);
		
	//	echo "\t<th align=right valign=top>$veckoDag</th>\n\t<th align=right valign=top>$day</th>\n\t<th align=left valign=top>$monthSwe</th>\n\t<th align=left valign=top>kl.$niceTime</th>\n";
		if (!$done)
		{
			$nextRehersalDate = $year . "-" . $month . "-" . $day;
			$done = TRUE;
		}
	       else
		{
			echo "<td></td>\n";
		}
		echo "<td valign=top>$row[note]</td>\n";
		echo "</tr>\n"; 
	}	
	echo "</table><p>";

	echo "<table>";
	echo "<tr><td>";


	echo "</td></tr>\n</table></ul>";

	if ($debug) up_note("ubd_gi_view: EXIT", $level);
}

// Returns -1 if not found
function ubd_gi_get_gig_id($debug, $bandID, $date)
{
	$query = "SELECT * FROM bands.gigs WHERE bandID='$bandID' AND date = '$date'";
  	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result))
	{
		return $row[gigID];
	}	
	return -1;
}

function ubd_gi_print_detail_form($debug, $bandMgmtPHP, $bandID, $gigID, $bandMgmtPHP, $edit)
{
	ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
	ubd_gi_get_detailed_data($debug,  $gigID, &$accessTime, &$gatheringTime, &$departureTime, &$unpackTime, &$soundCheckTime, &$soundCheckEndTime, &$onStageTime, &$gatheringDate, &$departureDate, &$unpackDate, &$soundCheckDate);
	if ($edit)
	{
		up_form_header($bandMgmtPHP);
		up_php_param("action", "update_gig_details", "hidden");	
		up_php_param("bandID", "$bandID", "hidden");	
		up_php_param("gigID", "$gigID", "hidden");	
	}	
	$tableColumn = 1; $valign="center";
	up_uls();
	up_table_header();
	up_table_column("Nedpackning: ", 1, "right");
	if (!$edit)
	{
		if ($gatheringTime == ""  || $gatheringTime == "00:00")
			$gatheringTime = "";
		up_table_column($gatheringTime);
		if ($gatheringDate != "")
			$gatheringDate = uc_get_nice_date($gatheringDate, 10, 1, $withYear);
		up_table_column($gatheringDate);
	}
	else
	{
		up_php_param("gatheringTime", $gatheringTime, $type, $tableColumn, 5);
		uc_print_date_option_menu_from($debug, $date, 2, 1, $gatheringDate, $tableColumn, $colSpan, "gatheringDate");
	}
	up_new_table_row();
	up_table_column("Avfärd: ", 1, "right");
	if (!$edit)
	{
		if ($departureTime == "" || $departureTime == "00:00") $departureTime = "";
		up_table_column($departureTime);
		if ($departureDate != "")
			$departureDate = uc_get_nice_date($departureDate, 10, 1, $withYear);
		up_table_column($departureDate);
	}
	else
	{
		up_php_param("departureTime", $departureTime, $type, $tableColumn, 5);
		uc_print_date_option_menu_from($debug, $date, 2, 1, $departureDate, $tableColumn, $colSpan, "departureDate");
	}
	// RIGGNING 
	up_new_table_row();
	up_table_column("Riggning: ", 1, "right");
	if (!$edit)
	{
		if ($unpackTime == "" || $unpackTime == "00:00") $unpackTime = "";
		up_table_column($unpackTime);
		if ($unpackDate != "")
			$unpackDate = uc_get_nice_date($unpackDate, 10, 1, $withYear);
		up_table_column($unpackDate);
	}
	else
	{
		up_php_param("unpackTime", $unpackTime, $type, $tableColumn, 5);
		uc_print_date_option_menu_from($debug, $date, 2, 1, $unpackDate, $tableColumn, $colSpan, "unpackDate");
	}
	// SOUND CHECK 
	up_new_table_row();
	up_table_column("Soundcheck: ", 1, "right");
	if (!$edit)
	{
		if ($soundCheckTime == "" || $soundCheckTime == "00:00") $soundCheckTime = "";
		up_table_column($soundCheckTime);
		if ($soundCheckDate != "")
			$soundCheckDate = uc_get_nice_date($soundCheckDate, 10, 1, $withYear);
		up_table_column($soundCheckDate);
	}
	else
	{
		up_php_param("soundCheckTime", $soundCheckTime, $type, $tableColumn, 5);
		uc_print_date_option_menu_from($debug, $date, 2, 1, $soundCheckDate, $tableColumn, $colSpan, "soundCheckDate");
	}
	// GUESTS ARRIVE
	$niceDate = uc_get_nice_date($date, 10, 1, $withYear);
	up_new_table_row();
	up_table_column("Gäster anländer: ", 1, "right");
	if (!$edit)
	{
		if ($soundCheckEndTime == "" || $soundCheckEndTime == "00:00") 
			$soundCheckEndTime = "";
		up_table_column($soundCheckEndTime);
	}
	else
		up_php_param("soundCheckEndTime", $soundCheckEndTime, $type, $tableColumn, 5);

	up_table_column($niceDate); // GUEST ARRIVES ALWAYS on the PARTY day
	// PÅ SCEN
	up_new_table_row();
	up_table_column("På scen: ", 1, "right");
	if (!$edit)
	{
		if ($onStageTime == "" || $onStageTime == "00:00") $onStageTime = "TBD";
		up_table_column($onStageTime);
	}
	else
		up_php_param("onStageTime", $onStageTime, $type, $tableColumn, 5);
	up_table_column($niceDate);
	if ($edit)
	{
		up_new_table_row();
		up_table_column();
		echo "<td>";
		ub_save();
		ub_cancel("$bandMgmtPHP?action=view_gig&bandID=$bandID&gigID=$gigID");
		echo "</td>";
		up_form_footer();
	}
	up_table_footer();
	up_ule();
}

// PRINTs SPECIFIC  GIG
function ubd_gi_view_gig($debug, $bandID, $gigID, $bandMgmtPHP, $stateFlag, $travelID, $date) // If gigID == "" => View All
{
	if (($gigID == "" || $gigID < 0) && $date == "")
	{
		up_error("ubd_gi_view_gig(gigID:'$gigID'/date:'$date') invalid NULL parameter.");
		return;
	}
	//$debug = 1;
	if ($debug) up_note("ubd_gi_view_gig($debug, bID:$bandID, gID:$gigID): ENTER", $level);
	$level = 1;
	ubdc_get_band_data($debug, $level, $bandID, $bandName, $bandDB);
	if ($gigID == "" || $gigID < 0)
	{
		$gigID = ubd_gi_get_gig_id($debug, $bandID, $date);
	}
	ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
	//ubd_gi_get_data();

/*
	echo "<center><h2><a href=$bandMgmtPHP?action=view_gig&gigID=$gigID&bandID=$bandID>$eventType</a>";
	ub_edit("$bandMgmtPHP?action=edit_gig&bandID=$bandID&gigID=$gigID", 20);
	echo "</h2>	
		*/
	//echo "<center><h3>";
	$veckodag = uc_get_swedish_weekday($date, $year, $month, $day, $nofChars);
	//$manad = uc_get_swedish_month($date, $year, $month, $day, $nofChars);
	$manad = uc_get_nice_date($date, 20);
	//echo "$veckodag" . "en $manad" . "$year";	
//	echo "</center>";

	// ==============
	//		NÄR?
	// ==============
	echo "<h3>När?";
	if ($stateFlag == "EDIT_GIG_DETAILS")
		ub_cancel("$bandMgmtPHP?action=view_gig&bandID=$bandID&gigID=$gigID", 20);
	else
		ub_edit("$bandMgmtPHP?action=edit_gig_details&bandID=$bandID&gigID=$gigID", 20);
	echo "$veckodag" . "en $manad" . "$year";	
	echo "</h3>";
	$tableColumn = 1;
	if ($stateFlag == "EDIT_GIG_DETAILS")
		ubd_gi_print_detail_form($debug, $bandMgmtPHP, $bandID, $gigID, $bandMgmtPHP, 1);
	else
		ubd_gi_print_detail_form($debug, $bandMgmtPHP, $bandID, $gigID, $bandMgmtPHP, 0);
	// ==============
	//		VAR?
	// ==============
	echo "<h3>Var?";
	if ($stateFlag == "EDIT_ARENA_DETAILS")
		ub_cancel("$bandMgmtPHP?action=view_gig&bandID=$bandID&gigID=$gigID", 20);
	else
		ub_edit("$bandMgmtPHP?action=edit_arena_details&bandID=$bandID&gigID=$gigID", 20);	
	echo "</h3>";
	ubdc_get_arena_data($debug, $dbase, $arenaID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL);
	up_uls();
	up_table_header();
	//up_table_column("Plats: ", 1, "right");
	if ($stateFlag == "EDIT_ARENA_DETAILS")
	{
		// EDIT
		ubd_gi_print_arena_data($debug, $arenaID, 1, $bandMgmtPHP, $bandID, $gigID);	
	}
	else
	{
		// VIEW
		ubd_gi_print_arena_data($debug, $arenaID, 0, $bandMgmtPHP, $bandID, $gigID);	
	
	}

	up_ule();
	// ==============
	//		LÅTAR?
	// ==============
	echo "<h3>Låtar?";
	if ($stateFlag == "EDIT_REPERTOIR_DETAILS")
		ub_cancel("$bandMgmtPHP?action=view_gig&bandID=$bandID&gigID=$gigID", 20);
	else
		ub_edit("$bandMgmtPHP?action=edit_repertoir_details&bandID=$bandID&gigID=$gigID", 20);	
	echo "</h3>";
	up_uls();
	$disabled=1;
	ubd_rp_get_repertoir_data($debug, $repertoirID, &$repertoirName, &$nofSets, &$pdf, &$lastUpdateDate,  &$lastUpdateTime, &$short_pdf);
	up_table_header();
	up_table_column("Låtlista: ", 1, "right");	
	if ($stateFlag == "EDIT_REPERTOIR_DETAILS")
	{
		$disabled=0;
		up_form_header($bandMgmtPHP);
		up_php_param("action", "update_gig", "hidden");
		up_php_param("bandID", $bandID, "hidden");
		up_php_param("gigID", $gigID, "hidden");
		ubd_rp_print_repertoir_option_menu($debug, $bandID, $repertoirID, $disabled, $tableColumn);
		ub_save();
		up_form_footer();
		$disabled=1;
	}
	else
		ub_nice_link("$bandMgmtPHP?action=view_repertoir&bandID=$bandID&rID=$repertoirID", $repertoirName, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor);

	//ubd_rp_print_repertoir_option_menu($debug, $bandID, $repertoirID, $disabled, $tableColumn);
	if ($short_pdf != "")
	{
		// echo "Short:";
		ub_pdf($short_pdf, $height, $target, "short PDF", $tableColumn, $right, $valign, $columnWidth);		
	}
	if ($pdf != "")
	{
		$longHeight = 20+5;
		ub_pdf($pdf, $longHeight, $target, "long PDF", $tableColumn, $right, $valign, $columnWidth);
	}
	up_table_footer();	
	up_ule();
	echo "<h3>Boende?";
	if ($stateFlag == "EDIT_HOTEL")
	{
		ub_cancel("$bandMgmtPHP?action=view_gig&bandID=$bandID&gigID=$gigID", 15);
		ubd_gi_get_accommodation_data($debug, $gigID, &$hotelID, &$bookingReference);
		ubd_gi_print_hotel_data($debug, $hotelID, 1, $bandMgmtPHP, $bandID, $gigID);
	}
	else if (ubd_gi_has_accomondation($gigID))
		ub_edit("$bandMgmtPHP?action=edit_hotel&bandID=$bandID&gigID=$gigID", 15);
	else
		ub_add("$bandMgmtPHP?action=print_add_hotel_form&bandID=$bandID&gigID=$gigID", 15);	
	echo "</h3>";
	if(ubd_gi_has_accomondation($gigID) && $stateFlag != "EDIT_HOTEL")
	{
		ubd_gi_get_accommodation_data($debug, $gigID, &$hotelID, &$bookingReference);
		ubd_gi_print_hotel_data($debug, $hotelID, 0, $bandMgmtPHP, $bandID, $gigID);
	}
	else if ($stateFlag == "ADD_ACCOMODATION")
	{
		//ubd_gi_print_hotel_form();
		$action = "add_hotel";
		$cancelAction = "view_gig&gigID=$gigID";
		ubdc_print_add_building_form($debug, $bandMgmtPHP, $action, $bandID, $cancelAction, $city, $gigID);
	}
	echo "<h3>Resor?";
	ub_add("$bandMgmtPHP?action=print_add_travel_form&bandID=$bandID&gigID=$gigID", 15);
	echo "</h3>";
	if ($stateFlag == "ADD_TRAVEL")
	{
		//ubd_gi_print_hotel_form();
		$action = "add_travel";
		$cancelAction = "view_gig&gigID=$gigID";
		ubd_gi_print_add_travel_form($debug, $bandMgmtPHP, $action, $bandID, $cancelAction, $city, $gigID);
		ubd_gi_print_travel_arrangements($debug, $gigID);
	}
	else
	{
	//	up_uls();
		ubd_gi_print_travel_arrangements($debug, $gigID, $bandID, $bandMgmtPHP, $travelID, $stateFlag);
	//	up_ule();
	}

	if ($debug) up_note("ubd_gi_view_gig: EXIT", $level);
}

// ============================================
//	ADD - GIG
// ============================================
// include ("include/util_band_common_include.php");
// 	    ubd_gi_add($debug, $bandID, $date, $time, $gigAddress, $placelink, $streetAddress, $city, $rID, $contact, $email, $phone, $company, $eventType, $soundName, $soundPhone, $soundEmail, $soundCompany, $siteName, $sitePhone, $siteEmail, $siteCompany, $mapname, $wikiMapLink, $travelName, $travelPhone, $travelEmail, $travelCompany  ); 

function ubd_gi_add($debug, $bandID, $date, $time, $eventType, $arenaID, $bandMgmtPHP, $repertoirID)

 //$addressID, $placelink, $streetAddress, $city, $rID, $contact, $email, $phone, $company, 
// $eventType, $soundName, $soundPhone, $soundEmail, $soundCompany, 
// $siteName, $sitePhone, $siteEmail, $siteCompany, $mapname, $wikiMapLink, 
// $travelName, $travelPhone, $travelEmail, $travelCompany )
{
	// $debug = 1;
	if ($debug) up_note("ubd_gi_add: ENTER");
	$today = date("Y-m-d");
	if ($bandID == "" || $date == "" || $time == "" )  // || $eventType == ""
	{
		// Let the client recall the _print_form function with error flag.
		up_error("Invalid NULL values ubd_gi_add($debug, bid:$bandID, date:$date, time:$time, eventType:$eventType)");
		return -1;
	}
	if(ubd_gi_exist($debug, $bandID, $date, $time))
	{
		//ubd_gi_print_form($debug, $bandID, 0, $gigID, $date, $time, $notes, $arenaID, $bandMgmtPHP, "ADD");

		up_note("Det finns redan en spelning registrerat $date kl.$time.");
		return -1;
	}
	else if (!uc_is_valid_date($debug, $date))
	{
		// Let the client recall the _print_form function with error flag.
		up_note("Invalid date $date kl.$time.");
		return 0;	
	}
	else if ($date < $today)
	{
		up_error("Datumet har redan inträffat.");
		// ubd_gi_print_form($debug, $bandID, 0, $gigID, $date, $time, $notes, $arenaID, $bandMgmtPHP, "ADD");
		return -1;	
	}
	$level = 1;
	if ($debug) up_note("ubd_gi_add: ENTER ", $level);
//	$bandDB = ubdc_get_band_db($debug, $bandID);
    if (ubd_gi_DateAlreadyBooked($bandDB, $date, $time))
    {
		up_error("Datum och tid är redan bokad för en annan spelning.");
		return -1;
   	}
   	
   	if ($bandID)
	{
		$variables = "bandID"; 
		$parameters = "'$bandID'"; 
	}
	if ($date)
	{
		$variables = $variables . ", date"; 
		$parameters = $parameters . ", '$date'"; 
	}
	if ($time)
	{
		$variables = $variables . ", time"; 
		$parameters = $parameters . ", '$time'"; 
	}
	if ($eventType)
	{
		$variables = $variables . ", eventType"; 
		$eventType = up_clean_var($eventType);
		$parameters = $parameters . ", '$eventType'"; 
	}
	if ($arenaID)
	{
		$variables = $variables . ", arenaID"; 
		$parameters = $parameters . ", '$arenaID'"; 
	}
	if ($repertoirID)
	{
		$variables = $variables . ", repertoirID"; 
		$parameters = $parameters . ", '$repertoirID'"; 
	}
   	$gigID = ubdc_get_unique_id($debug, "bands", "gigs", "gigID");

	if ($gigID > 0)
	{
		$variables = $variables . ", gigID"; 
		$parameters = $parameters . ", '$gigID'"; 	
	}	
	else
	{
		up_error("ubd_gi_add: Unique identifier invalid($gigID).");
		return -1;
	}

    $sql = "INSERT INTO bands.gigs ( $variables ) VALUES ( $parameters)";
    $result = mysql_query ($sql);	
  	if ($debug) up_note($sql);
      
    $returnValue = 1;
    if ($result == FALSE)
    {
       	 up_error("Unable to add the event in the schedule.");
       	 $msg = mysql_error();
       	 if($msg)
          	 up_error("ubd_gi_add: $msg");
          $returnValue = 0;
    }

	if ($debug) up_note("ubd_gi_add: EXIT", $level);
	return $returnValue;
}

function ubd_gi_DateAlreadyBooked($bandID, $date, $time)
{
 	$level = 1;
  	$query = "SELECT * FROM bands.gigs WHERE gigDate='$date' AND gigTime='$time' AND bandID='$bandID'"; 
  	$result = mysql_query ($query);
	
	while ($row = mysql_fetch_array ($result))
	{
		return TRUE;
	}
	return FALSE;
}

// ============================================
//	UPDATE - GIG
// ============================================

function ubd_gi_update($debug, $bandID, $gigID, $date, $time, $eventType, $arenaID, $repertoirID, $targetPHP)
{
	// $debug = 1;
	if ($debug) up_note("ubd_gi_update(dbug:$debug, bID:$bandID, gID:$gigID, date:$date, time:$time, aID:$arenaID, rID:$repertoirID): ENTER");

	if ($bandID== ""|| $gigID == "" )  // Not necessary when editing (old date/time values will be used)|| $date == ""|| $time == "")
	{
		if ($bandID == "")
			up_error("ubd_gi_update($debug, bID:$bandID, gID:$gigID, date:$date, time:$time, eType:$eventType, aID:$arenaID, $repertoirID):Invalid NULL parameters.");
		else
			ubd_gi_add($debug, $bandID, $date, $time, $eventType, $arenaID, $targetPHP, $repertoirID);
		return;
	}
	// MUST be given (first item in the list of parameters)
	$variables = "bandID='$bandID'"; 

	if ($date)
	{
		$variables = $variables . ", date='$date'"; 
	}
	if ($time)
	{
		$variables = $variables . ", time='$time'"; 
	}
	if ($eventType)
	{
		$eventType = up_clean_var($eventType);
		$variables = $variables . ", eventType='$eventType'"; 
	}	
	if ($arenaID)
	{
		$variables = $variables . ", arenaID='$arenaID'"; 
	}	
	if ($repertoirID)
	{
		$variables = $variables . ", repertoirID='$repertoirID'"; 
	}	
	$sql = "UPDATE bands.gigs SET $variables WHERE gigID=$gigID";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_gi_update: Unable to update the post. (sql:$sql)");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
    else
    {
    	if ($debug) up_info("Updated gig successfully - $bandID, $gigID, $date, $time, $eventType, $arenaID, $repertoirID");
    }
	if ($debug) up_note("ubd_gi_update: EXIT");	
}

function ubd_gi_update_hotel_booking_reference($debug, $gigID, $hotelBookingReference)
{
	if ($debug) up_note("ubd_gi_update_hotel_booking_reference($debug, gID:$gigID, bookingReference:'$hotelBookingReference'): ENTER");	
	$variables = "gigID='$gigID'"; 
	if ($hotelBookingReference)
	{
		$variables = $variables . ", hotelBookingReference='$hotelBookingReference'"; 
	}
	$sql = "UPDATE bands.gigs SET $variables WHERE gigID=$gigID";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_gi_update_hotel_booking_reference: Unable to update the post. (sql:$sql)");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
    else
    {
    	if ($debug) up_info("ubd_gi_update_hotel_booking_reference: EXIT");
    }
	if ($debug) up_note("ubd_gi_update_hotel_booking_reference: EXIT");	
}

function ubd_gi_gig_details_exist($debug, $gigID)
{
  	$query = "SELECT * FROM bands.gig_details WHERE gigID='$gigID'"; 
  	$result = mysql_query ($query);
	
	while ($row = mysql_fetch_array ($result))
	{
		return TRUE;
	}
	return FALSE;
	
}

// Adds or updates
function ubd_gi_update_gig_details( $debug, $bandID, $gigID, $accessTime, $gatheringTime, $departureTime, $unpackTime, $soundCheckTime, $soundCheckEndTime, $onStageTime, $gatheringDate, $departureDate, $unpackDate, $soundCheckDate )
{
	if ($debug) up_info("ubd_gi_update_gig_details( $debug, bID:$bandID, gID:$gigID, aTime:$accessTime, gathTime:$gatheringTime, depTime:$departureTime, uPackTime:$unpackTime, sCheckTime:$soundCheckTime, scheckEndTime:$soundCheckEndTime, onStageTime:$onStageTime, gDate:$gatheringDate, depDate:$departureDate, unpackDate:$unpackDate, soundcheckDate:$soundCheckDate ):");
	if (ubd_gi_gig_details_exist($debug, $gigID))
	{
		// UPDATE
		$variables = "gigID='$gigID'"; 

		if ($accessTime)
		{
			$variables = $variables . ", accessTime='$accessTime'"; 
		}
		if ($gatheringTime)
		{
			$variables = $variables . ", gatheringTime='$gatheringTime'"; 
		}
		if ($departureTime)
		{
			$eventType = up_clean_var($departureTime);
			$variables = $variables . ", departureTime='$departureTime'"; 
		}	
		if ($unpackTime)
		{
			$variables = $variables . ", unpackTime='$unpackTime'"; 
		}	
		if ($soundCheckTime)
		{
			$variables = $variables . ", soundCheckTime='$soundCheckTime'"; 
		}	
		if ($soundCheckEndTime)
		{
			$variables = $variables . ", soundCheckEndTime='$soundCheckEndTime'"; 
		}	
		if ($onStageTime)
		{
			$variables = $variables . ", onStageTime='$onStageTime'"; 
		}	
		if ($gatheringDate)
		{
			$variables = $variables . ", gatheringDate='$gatheringDate'"; 
		}	
		if ($departureDate)
		{
			$variables = $variables . ", departureDate='$departureDate'"; 
		}	
		if ($unpackDate)
		{
			$variables = $variables . ", unpackDate='$unpackDate'"; 
		}	
		if ($soundCheckDate)
		{
			$variables = $variables . ", soundCheckDate='$soundCheckDate'"; 
		}			
		$sql = "UPDATE bands.gig_details SET $variables WHERE gigID=$gigID";

    	$result = mysql_query ($sql);	
        
    	if ($result == FALSE)
    	{
			up_error("ERROR: ubd_gi_update_gig_details: Unable to update the post.");
			$msg = mysql_error();
			if ($msg)
				up_error($msg);
			return -1;
    	} 		
    	else
    	{
    		if ($debug) up_info("Updated gig details successfully.");
    	}
	}
	else	// ADD
	{
		$variables = "gigID"; 
		$parameters = "'$gigID'"; 		
		if ($accessTime)
		{
			$variables = $variables . ", accessTime"; 
			$parameters = $parameters . ", '$accessTime'"; 
		}
		if ($gatheringTime)
		{
			$variables = $variables . ", gatheringTime"; 
			$parameters = $parameters . ", '$gatheringTime'"; 
		}
		if ($departureTime)
		{
			$variables = $variables . ", departureTime"; 
			$eventType = up_clean_var($departureTime);
			$parameters = $parameters . ", '$departureTime'"; 
		}
		if ($unpackTime)
		{
			$variables = $variables . ", unpackTime"; 
			$eventType = up_clean_var($unpackTime);
			$parameters = $parameters . ", '$unpackTime'"; 
		}
		if ($soundCheckTime)
		{
			$variables = $variables . ", soundCheckTime"; 
			$eventType = up_clean_var($soundCheckTime);
			$parameters = $parameters . ", '$soundCheckTime'"; 
		}
		if ($soundCheckEndTime)
		{
			$variables = $variables . ", soundCheckEndTime"; 
			$eventType = up_clean_var($soundCheckEndTime);
			$parameters = $parameters . ", '$soundCheckEndTime'"; 
		}
		if ($onStageTime)
		{
			$variables = $variables . ", onStageTime"; 
			$eventType = up_clean_var($onStageTime);
			$parameters = $parameters . ", '$onStageTime'"; 
		}
		if ($gatheringDate)
		{
			$variables = $variables . ", gatheringDate"; 
			$eventType = up_clean_var($gatheringDate);
			$parameters = $parameters . ", '$gatheringDate'"; 
		}	
		if ($departureDate)
		{
			$variables = $variables . ", departureDate"; 
			$eventType = up_clean_var($departureDate);
			$parameters = $parameters . ", '$departureDate'"; 
		}	
		if ($unpackDate)
		{
			$variables = $variables . ", unpackDate"; 
			$eventType = up_clean_var($unpackDate);
			$parameters = $parameters . ", '$unpackDate'"; 
		}	
		if ($soundCheckDate)
		{
			$variables = $variables . ", soundCheckDate"; 
			$eventType = up_clean_var($soundCheckDate);
			$parameters = $parameters . ", '$soundCheckDate'"; 
		}			
   	 	$sql = "INSERT INTO bands.gig_details ( $variables ) VALUES ( $parameters)";
    	$result = mysql_query ($sql);	
  		if ($debug) up_note($sql);
      
    	$returnValue = $hotelID;
    	if ($result == FALSE)
    	{
       	 	up_error("ubd_gi_update_gig_details: Unable to add the gig details in the database.");
       	 	up_error("ubd_gi_update_gig_details: sql:$sql.");
       	 	$msg = mysql_error();
       	 	if($msg)
          	 	up_error("ubd_gi_update_gig_details: $msg");
          	$returnValue = 0;
    	}	
    	return  $returnValue;				
	}
}


// ============================================
//	DELETE - GIG
// ============================================

function ubd_gi_delete($debug, $bandID, $gigID)
{
	$level = 1;
	if ($debug) up_note("ubd_gi_delete($debug, $bandID, $gigID): ENTER", $level);
	$sql = "DELETE FROM bands.gigs WHERE gigID='$gigID' AND bandID='$bandID'";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_gi_delete: Unable to update the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
	if ($debug) up_note("ubd_gi_delete: EXIT", $level);
}	

// ============================================
//	GIG ARENA
// ============================================

function 	ubd_gi_get_arena_data($debug, $arenaID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL, &$photo)
{
	// up_error("ubd_gi_get_arena_data: NYI");
	$query = "SELECT * FROM bands.arena WHERE uID=$arenaID";	
  	$result = mysql_query ($query);	
	while ($row = mysql_fetch_array ($result))
	{
		$name			= $row[name];
		$streetAddress	= $row[streetAddress];
		$city			= $row[city];
		$postalCode		= $row[postalCode];
		$phoneNumber	= $row[phoneNumber];
		$URL			= $row[URL];
		$photo			= $row[photo];
	}
}

// To VIEW and EDIT arena data
function ubd_gi_print_arena_data($debug, $arenaID, $edit, $targetPHP, $bandID, $gigID)
{
	// echo "<li> BEFORE arenaID='$arenaID'";
	$tableColumn = 1;
	// $debug  = 1;
	ubd_gi_get_arena_data($debug, $arenaID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL, &$photo);
	up_uls();
	// echo "<li> AFTER arenaID='$arenaID'";
	if ($edit)
	{
		up_form_header($targetPHP);
		up_php_param("action", "update_arena_details", "hidden");
		up_php_param("arenaID", $arenaID, "hidden");
		up_php_param("bandID", $bandID, "hidden");
		up_php_param("gigID", $gigID,  "hidden");
	}
	$valign = "top";	
	up_table_header();
	up_table_column("Arena: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(name, $name,  $type, $tableColumn, $size);
	else
	{
		up_table_column($name, 0, "left");
		if ($photo)
		{
// up_image($image, $width, $height, $tableColumn, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, $identity)
			$rowSpan = 15;
			$height=200;
			up_image($photo, $width, $height, $tableColumn, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, $identity);
		}	
	}
	up_new_table_row();
	up_table_column("Gatuadress: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(streetAddress, $streetAddress,  $type, $tableColumn, $size);
	else
	{
		if ($streetAddress && $city)
		{
			$URL_street = str_replace(" ", "%20", $streetAddress);
			$hittaURL = "http://www.hitta.se/sök?vad=" . $URL_street . "%2C%20" . $city . "&typ=plats";
			ub_nice_link($hittaURL, $streetAddress, "newWindow", "hitta.se", $tableColumn);
		}
		else
			up_table_column($streetAddress, 0, "left");
	}
	up_new_table_row();
	up_table_column("Stad: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(city, $city,  $type, $tableColumn, $size);
	else
		up_table_column($city, 0, "left");
	up_new_table_row();
	up_table_column("Telefon: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(phoneNumber, $phoneNumber,  $type, $tableColumn, $size);
	else
		up_table_column($phoneNumber, 0, "left");
	up_new_table_row();
	$valign="top";
	up_table_column("URL: ", 1, "right", "", $valign);
	if ($URL && !$edit)
	{
		ub_nice_link($URL, $name, "newWindow", "Mer info", $tableColumn);
		up_new_table_row();
	}
	else if ($edit)
	{
		$size = 50;
		up_php_param(URL, $URL,  $type, $tableColumn, $size);
	}
	if ($edit)
	{
		up_new_table_row();
		up_table_column("Foto: ", 1, "right", "", $valign);	
		up_php_param("photo", "", "file", $tableColumn);
		up_new_table_row();
		up_table_column("");
		echo "<td>";
		ub_save();
		// ub_cancel();
		up_form_footer();
		echo "</td>";
	}
	up_table_footer();
	up_ule();
}

// ============================================
//	GIG ACCOMODATION  (uID, gigID, hotelID, bookingReference)
// ============================================

function ubd_gi_get_accommodation_data($debug, $gigID, &$hotelID, &$bookingReference)
{
	$query = "SELECT * FROM bands.accomodation WHERE gigID=$gigID";	
  	$result = mysql_query ($query);	
	while ($row = mysql_fetch_array ($result))
	{
		$hotelID			= $row[hotelID];
		$bookingReference	= $row[bookingReference];
	}
}

function ubd_gi_has_accomondation($gigID)
{
	$query = "SELECT * FROM bands.accomodation WHERE gigID=$gigID";	
  	$result = mysql_query ($query);	
	while ($row = mysql_fetch_array ($result))
	{
		return TRUE;
	}
	return FALSE;
}

function ubd_gi_add_gig_accommodation($debug, $gigID, $hotelID, $bookingReference)
{
   	$accomodationID = ubdc_get_unique_id($debug, "bands", "accomodation", "uID");

	if ($accomodationID > 0)
	{
		$variables = "uID"; 
		$parameters = "'$accomodationID'"; 
	}	
	else
	{
		up_error("ubd_gi_add_gig_accommodation: Unique identifier invalid ($accomodationID).");
		return;
	}

	if ($gigID)
	{
		$variables = $variables . ", gigID"; 
		$parameters = $parameters . ", '$gigID'"; 
	}
	if ($hotelID)
	{
		$variables = $variables . ", hotelID"; 
		$parameters = $parameters . ", '$hotelID'"; 
	}
	if ($bookingReference)
	{
		$variables = $variables . ", bookingReference"; 
		$eventType = up_clean_var($bookingReference);
		$parameters = $parameters . ", '$bookingReference'"; 
	}

    $sql = "INSERT INTO bands.accomodation ( $variables ) VALUES ( $parameters)";
    $result = mysql_query ($sql);	
  	if ($debug) up_note($sql);
      
    $returnValue = $hotelID;
    if ($result == FALSE)
    {
       	 up_error("ubd_gi_add_gig_accommodation: Unable to add the hotel in the database.");
       	 $msg = mysql_error();
       	 if($msg)
          	 up_error("ubd_gi_add_gig_accommodation: $msg");
          $returnValue = 0;
    }	
    return  $returnValue;	
}

// ============================================
//	GIG HOTEL
// ============================================

function ubd_gi_get_hotel_data($debug, $hotelID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL, &$photo)
{
	if ($hotelID <= 0)
	{
		up_error("Invalid hotelID '$hotelID'.");
		return;
	}
	if ($debug) up_info(" ubd_gi_get_hotel_data($debug, $hotelID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL)");
	$query = "SELECT * FROM bands.hotels WHERE uID='$hotelID'";
  	$result = mysql_query ($query);
	
	$name = "not found";
	while ($row = mysql_fetch_array ($result))
	{
		$name			= $row[name];
		$streetAddress	= $row[streetAddress];
		$city			= $row[city];
		$postalCode		= $row[postalCode];
		$phoneNumber	= $row[phoneNumber];
		$URL			= $row[URL];
		$photo			= $row[photo];
	}
}

// To VIEW and EDIT hotel data
function ubd_gi_print_hotel_data($debug, $hotelID, $edit, $targetPHP, $bandID, $gigID)
{
	//$debug = 1;
	if ($debug) up_info("ubd_gi_print_hotel_data($debug, $hotelID, $edit, $targetPHP, $bandID, $gigID)");
	$tableColumn = 1;
	// $debug  = 1;
	ubd_gi_get_hotel_data($debug, $hotelID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL, &$photo);
	up_uls();
	if ($edit)
	{
		up_form_header($targetPHP);
		up_php_param("action", "update_hotel", "hidden");
		up_php_param("hotelID", $hotelID, "hidden");
		up_php_param("bandID", $bandID, "hidden");
		up_php_param("gigID", $gigID,  "hidden");
	}
	$valign = "center";	
	up_table_header();
	up_table_column("Hotell: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(name, $name,  $type, $tableColumn, $size);
	else
	{
		up_table_column($name, 0, "left");

	}	
		if ($photo)
		{
// up_image($image, $width, $height, $tableColumn, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, $identity)
			$rowSpan = 7;
			$height=200;
			up_image($photo, $width, $height, $tableColumn, $noResize, $maxWidth, $maxHeight, $rowSpan, $valign, $colSpan, $identity);
		}		

	up_new_table_row();
	up_table_column("Gatuadress: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(streetAddress, $streetAddress,  $type, $tableColumn, $size);
	else
	{
		if ($streetAddress && $city)
		{
			$URL_street = str_replace(" ", "%20", $streetAddress);
			$hittaURL = "http://www.hitta.se/sök?vad=" . $URL_street . "%2C%20" . $city . "&typ=plats";
			ub_nice_link($hittaURL, $streetAddress, "newWindow", "hitta.se", $tableColumn);
		}
		else
			up_table_column($streetAddress, 0, "left");
	}
	up_new_table_row();
	up_table_column("Stad: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(city, $city,  $type, $tableColumn, $size);
	else
		up_table_column($city, 0, "left");
	up_new_table_row();
	up_table_column("Telefon: ", 1, "right", "", $valign);
	if ($edit)
		up_php_param(phoneNumber, $phoneNumber,  $type, $tableColumn, $size);
	else
		up_table_column($phoneNumber, 0, "left");
	up_new_table_row();
	$valign="top";
	up_table_column("URL: ", 1, "right", "", $valign);
	if ($URL && !$edit)
	{
	
		ub_nice_link($URL, $name, "newWindow", "Mer info", $tableColumn);
		up_new_table_row();
	}
	else if ($edit)
	{
		$size = 50;
		up_php_param(URL, $URL,  $type, $tableColumn, $size);

	}
	up_new_table_row();
	up_table_column("Bokningsreferens: ", 1, "right", "", $valign);
	ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID, &$hotelBookingReference);
	if ($edit)
	{	
		$size = 15;
		up_php_param("hotelBookingReference", $hotelBookingReference,  $type, $tableColumn, $size);
	}
	else
	{
		up_table_column($hotelBookingReference, 0, "left");	
	}
	$debug = 0;
	if ($edit)
	{
		up_new_table_row();
		up_table_column("Foto: ", 1, "right", "", $valign);	
		up_php_param("photo", "", "file", $tableColumn);
		up_new_table_row();
		up_table_column("");
		echo "<td>";
		ub_save();
		// ub_cancel();
		up_form_footer();
		echo "</td>";
	}
	up_table_footer();
	up_ule();
}

function ubd_gi_hotel_exist($debug, $name, $streetAddress, $city)
{
	$query = "SELECT * FROM bands.hotels WHERE name='$name' AND streetAddress='$streetAddress' AND city='$city'";
  	$result = mysql_query ($query);
	
	while ($row = mysql_fetch_array ($result))
	{
		return $row[uID];
	}
	
	return FALSE;
}

function ubd_gi_add_hotel($debug, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL)
{
	if (($hotelID = ubd_gi_hotel_exist($debug, $name, $streetAddress, $city)) > 0)
	{
		return $hotelID;
	}
   	$hotelID = ubdc_get_unique_id($debug, "bands", "hotels", "uID");

	if ($hotelID > 0)
	{
		$variables = "uID"; 
		$parameters = "'$hotelID'"; 
	}	
	else
	{
		up_error("ubd_gi_add_hotel: Unique identifier invalid ($hotelID).");
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
		$eventType = up_clean_var($eventType);
		$parameters = $parameters . ", '$city'"; 
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
	if ($URL)
	{
		$variables = $variables . ", URL"; 
		$parameters = $parameters . ", '$URL'"; 
	}
    $sql = "INSERT INTO bands.hotels ( $variables ) VALUES ( $parameters)";
    $result = mysql_query ($sql);	
  	if ($debug) up_note($sql);
      
    $returnValue = $hotelID;
    if ($result == FALSE)
    {
       	 up_error("ubd_gi_add_hotel: Unable to add the hotel in the database.");
       	 $msg = mysql_error();
       	 if($msg)
          	 up_error("ubd_gi_add_hotel: $msg");
          $returnValue = 0;
    }	
    return  $returnValue;
}

function ubd_gi_update_hotel($debug, $hotelID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL, $photo)
{
	if ($debug) up_info("ubd_gi_update_hotel($debug, $hotelID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL): ENTER");

	// MUST be given (first item in the list of parameters)
	$variables = "uID='$hotelID'"; 

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
		$eventType = up_clean_var($city);
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
	$sql = "UPDATE bands.hotels SET $variables WHERE uID=$hotelID";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_gi_update_hotel: Unable to update the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
    else
    {
    	if ($debug) up_info("Updated hotel successfully - $hotelID, $name, $streetAddress, $city, $postalCode, $phoneNumber, $URL, photo:'$photo'");
    }
	if ($debug) up_note("ubd_gi_update_hotel: EXIT");	

}

// ============================================
// GIG TRANSPORTATION
// ============================================

function ubd_gi_get_travel_data($debug, $travelID, &$typeOfTransportation, &$from, &$destination, &$date, &$departureTime, &$arrivalTime)
{
	if ($travelID <= 0)
	{
		up_error("Invalid travelID '$travelID'.");
		return;
	}
	if ($debug) up_info(" ubd_gi_get_travel_data($debug, $travelID, &$typeOfTransportation, &$from, &$destination, &$date, &$departureTime, &$arrivalTime)");
	$query = "SELECT * FROM bands.travel WHERE uID='$hotelID'";
  	$result = mysql_query ($query);
	
	$name = "not found";
	while ($row = mysql_fetch_array ($result))
	{
		$name			= $row[name];
		$streetAddress	= $row[streetAddress];
		$city			= $row[city];
		$postalCode		= $row[postalCode];
		$phoneNumber	= $row[phoneNumber];
		$URL			= $row[URL];
	}
	if ($debug) up_info(" ubd_gi_get_travel_data($debug, $travelID, $typeOfTransportation, $from, $destination, $date, $departureTime, $arrivalTime)");
}

function ubd_gi_print_travel_header()
{
	up_table_column("Bandmedlem", 1, "Left");
	up_table_column("Transport", 1, "Left");
	up_table_column("Från", 1, "Left");
	up_table_column("Till", 1, "Left");
	up_table_column("Datum", 1, "Left");
	up_table_column("Avgångstid", 1, "Left");
	up_table_column("Ankomsttid", 1, "Left");
	up_table_column("Bokn.ref.", 1, "Left");
	up_table_column("PDF", 1, "Left");
	up_new_table_row();
}

function ubd_gi_print_travel_row($debug, $date)
{
	$tableColumn = 1;
	ubdc_print_transport_type_option_menu($debug, $selectedTransport, $disabled, $tableColumn);
	$size = 30;
	up_php_param("from", "", "", $tableColumn, $size);
	up_php_param("destination", "", "", $tableColumn, $size);
	$size = 10;
	up_php_param("date", $date, "", $tableColumn, $size);
	$size = 8;
	up_php_param("departureTime", "", "", $tableColumn, $size);
	up_php_param("arrivalTime", "", "", $tableColumn, $size);
}

function ubd_gi_print_add_travel_form($debug, $bandMgmtPHP, $action, $bandID, $cancelAction, $city, $gigID)
{
	ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
	up_form_header($bandMgmtPHP);
	up_php_param("action", "add_travel", "hidden");
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("gigID", $gigID, "hidden");
	$tableColumn = 1; $valign = "center";
	up_table_header(0);
	//ubd_gi_print_travel_header();
	//ubd_gi_print_travel_row($debug, $date);
	// === PERSON === 
	up_table_column("Namn:", 1, "right", "", $valign);
	ubd_print_band_member_option_menu($debug, $selectedPerson, $tableColumn, $colSpan, $bgcolor, $bandID);
	up_new_table_row();
	// === TRANSPORT
	up_table_column("Transport: ", 1, "right", "", $valign);
	ubdc_print_transport_type_option_menu($debug, $selectedTransport, $disabled, $tableColumn);
	up_table_column("Tid: ", 1, "left", "", $valign);
	up_table_column("Datum: ", 1, "left", "", $valign);
	up_new_table_row();
	// === FRÅN === 
	$size = 30;
	up_table_column("Från: ", 1, "right", "", $valign);
	up_php_param("from", "", "", $tableColumn, $size);
	$size = 8;
	up_php_param("departureTime", "", "", $tableColumn, $size);
	$size = 10;
	//up_php_param("date", $date, "", $tableColumn, $size);
	$daysBack = 2;$daysForward = 4;
	$selectedDate = $date;
	// NEW
	uc_print_date_option_menu_from($debug, $selectedDate, $daysBack, $daysForward, $selectedDate, $tableColumn, $colSpan);
	// OLD
	// uc_print_date_option_menu($debug, $daysBack, $selectedDate, $tableColumn, $colSpan);
	up_new_table_row();
	// === TILL === 
	up_table_column("Till: ", 1, "right");
	$size = 30;
	up_php_param("destination", "", "", $tableColumn, $size);
	$size = 8;
	up_php_param("arrivalTime", "", "", $tableColumn, $size);	
	up_new_table_row();
	// === BOKNINGSREFERENS === 
	up_table_column("Bokningsreferens: ", 1, "right", "", $valign);
	$size = 10;
	up_php_param("bookingReference", "", "", $tableColumn, $size);
	// === TRAVEL DOCUMENTS === 
	up_new_table_row();
	up_table_column("Biljett:", 1, "right", "", $valign);
	$size = 20;
	up_php_param("travelDocuments", $value, "file", $tableColumn, $size);

	// === SPARA / ABRYT - knappar ===
	up_new_table_row();
	up_table_column("");
	ub_save("", 20, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
	ub_cancel("$bandMgmtPHP?bandID=$bandID&action=$cancelAction", 20, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
	up_table_footer();
	up_form_footer();
}

function ubd_gi_travel_exist($debug, $bookingReference, $userID, $date, $departureTime)
{
	$query = "SELECT * FROM bands.travel WHERE bookingReference='$bookingReference' AND userID='$userID' AND date='$date' AND departureTime='$departureTime'";	
  	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result))
	{
		return TRUE;
	}	
	return FALSE;
}	

function ubd_gi_add_travel($debug, $gigID, $typeOfTransportation, $from, $destination, $date, $departureTime, $arrivalTime, $bookingReference, $userID, $travelDocument)
{
	// $debug = 1;
	if ($debug) up_info("ubd_gi_add_travel($debug, $gigID, $typeOfTransportation, $from, $destination, $date, $departureTime, $arrivalTime, bRef:$bookingReference, user:$userID)");
	// CHECK parameters
	if ($gigID == "" ||  $typeOfTransportation == "" ||  $from== "" || $destination== "" ||$date== "" || $departureTime== "" || $arrivalTime== "" )
	{
		up_error("ubd_gi_add_travel: Invalid NULL parameters.");
		return -1;
	}	
	// CHECK if It already exist
	if (($travelID = ubd_gi_travel_exist($debug, $bookingReference, $userID, $date, $departureTime)) > 0)
	{
		up_info("The booking already exist.");
		return $hotelID;
	}
	
   	$travelID = ubdc_get_unique_id($debug, "bands", "travel", "uID");

	if ($travelID > 0)
	{
		$variables = "uID"; 
		$parameters = "'$travelID'"; 
	}	
	else
	{
		up_error("ubd_gi_add_travel: Unique identifier invalid ($travelID).");
		return;
	}

	if ($gigID)
	{
		$variables = $variables . ", gigID"; 
		$parameters = $parameters . ", '$gigID'"; 
	}
	if ($typeOfTransportation)
	{
		$variables = $variables . ", transportationType"; 
		$parameters = $parameters . ", '$typeOfTransportation'"; 
	}
	if ($from)
	{
		$variables = $variables . ", departureFrom"; 
		$from = up_clean_var($from);
		$parameters = $parameters . ", '$from'"; 
	}
	if ($destination)
	{
		$variables = $variables . ", destination"; 
		$parameters = $parameters . ", '$destination'"; 
	}
	if ($date)
	{
		$variables = $variables . ", date"; 
		$parameters = $parameters . ", '$date'"; 
	}
	if ($departureTime)
	{
		$variables = $variables . ", departureTime"; 
		$parameters = $parameters . ", '$departureTime'"; 
	}
	if ($arrivalTime)
	{
		$variables = $variables . ", arrivalTime"; 
		$parameters = $parameters . ", '$arrivalTime'"; 
	}
	if ($bookingReference)
	{
		$variables = $variables . ", bookingReference"; 
		$parameters = $parameters . ", '$bookingReference'"; 
	}
	if ($userID)
	{
		$variables = $variables . ", userID"; 
		$parameters = $parameters . ", '$userID'"; 
	}
	if ($travelDocument)
	{
		$variables = $variables . ", travelDocument"; 
		$parameters = $parameters . ", '$travelDocument'"; 	
	}

    $sql = "INSERT INTO bands.travel ( $variables ) VALUES ( $parameters)";
    $result = mysql_query ($sql);	
  	if ($debug) up_note($sql);
      
    $returnValue = $travelID;
    if ($result == FALSE)
    {
       	 up_error("ubd_gi_add_travel: Unable to add the travel in the database.");
       	 up_error("SQL: $sql");
       	 $msg = mysql_error();
       	 if($msg)
          	 up_error("ubd_gi_add_travel: $msg");
          $returnValue = 0;
    }	
    return  $returnValue;	
}

function ubd_gi_update_travel($debug, $gigID, $travelID, $typeOfTransportation, $from, $destination, $date, $departureTime, $arrivalTime, $bookingReference, $userID, $travelDocument)
{
	// $debug = 1;
	if ($debug) up_info("ubd_gi_update_travel($debug, $gigID, $travelID, $typeOfTransportation, $from, $destination, $date, $departureTime, $arrivalTime, bRef:$bookingReference, user:$userID, $travelDocument)");
	// CHECK parameters
	if ($gigID == "" ||  $travelID == "" || $typeOfTransportation == "" ||  $from== "" || $destination== "" ||$date== "" || $departureTime== "" || $arrivalTime== "" )
	{
		up_error("ubd_gi_update_travel: Invalid NULL parameters.");
		$debug = 1;
		if ($debug) up_info("ubd_gi_update_travel($debug, gID:$gigID, tID:$travelID, type:$typeOfTransportation, from:$from, dst:$destination, date:$date, dTime:$departureTime, aTime:$arrivalTime, bRef:$bookingReference, user:$userID, doc:$travelDocument)");
		return -1;
	}	
	// FIRST PARAMETER
	$variables = "uID='$travelID'"; 

	if ($gigID)
	{
		$variables = $variables . ", gigID='$gigID'"; 
	}
	if ($userID)
	{
		$variables = $variables . ", userID='$userID'"; 
	}
	if ($typeOfTransportation)
	{
		$variables = $variables . ", transportationType='$typeOfTransportation'"; 
	}
	if ($from)
	{
		$variables = $variables . ", departureFrom='$from'"; 
	}
	if ($destination)
	{
		$variables = $variables . ", destination='$destination'"; 
	}
	if ($date)
	{
		$variables = $variables . ", date='$date'"; 
	}
	if ($departureTime)
	{
		$variables = $variables . ", departureTime='$departureTime'"; 
	}
	if ($arrivalTime)
	{
		$variables = $variables . ", arrivalTime='$arrivalTime'"; 
	}
	if ($bookingReference)
	{
		$variables = $variables . ", bookingReference='$bookingReference'"; 
	}
	if ($travelDocument)
	{
		$variables = $variables . ", travelDocument='$travelDocument'"; 
	}


	$sql = "UPDATE bands.travel SET $variables WHERE uID=$travelID";
    $result = mysql_query ($sql);	
  	if ($debug) up_note($sql);
      
    $returnValue = $travelID;
    if ($result == FALSE)
    {
       	 up_error("travel: Unable to update the travel in the database.");
       	 up_error("SQL: $sql");
       	 $msg = mysql_error();
       	 if($msg)
          	 up_error("travel: $msg");
          $returnValue = 0;
    }	
    return  $returnValue;	
}

function ubd_gi_print_travel_arrangements($debug, $gigID, $bandID, $targetPHP, $travelID, $stateFlag)
{
	up_table_header(0);
	$query = "SELECT * FROM bands.travel WHERE gigID='$gigID' ORDER BY userID, date,  departureTime";
	$disabled = 1; $tableColumn = 1;
  	$result = mysql_query ($query);
  	$headerPrinted = 0;
	while ($row = mysql_fetch_array ($result))
	{
		if (!$headerPrinted)
		{
			ubd_gi_print_travel_header();
			$headerPrinted = 1;
		}
		ubd_get_band_member_data($row[userID], &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture);
		up_table_column("$firstName $lastName", 0, "Left");
		if ($stateFlag == "EDIT_TRAVEL" && $travelID == $row[uID])
		{
			//echo "HERE";
			up_form_header($targetPHP);
			up_php_param("action", "update_travel", "hidden", !$tableColumn, $size);
			up_php_param("userID", $row[userID],  "hidden", !$tableColumn, $size);
			up_php_param("bandID", $bandID,  "hidden", !$tableColumn, $size);
			up_php_param("gigID", $gigID,  "hidden", !$tableColumn, $size);
			up_php_param("travelID", $travelID,  "hidden", !$tableColumn, $size);
			
			// TRANSPORT TYOE
			ubdc_print_transport_type_option_menu($debug, $row[transportationType], !$disabled, $tableColumn);
			// FROM 
			up_php_param("departureFrom", $row[departureFrom],  $type, $tableColumn, $size); 
			// DESTINATION
			up_php_param("destination", $row[destination], $type, $tableColumn, $size);
			// DATE
			$size = 10;
			up_php_param("date", $row[date], $type, $tableColumn, $size);
			// DEPARTURE TIME
			$size = 5;
			up_php_param("departureTime", $row[departureTime], $type, $tableColumn, $size);
			// ARRIVAL TIME
			$size = 5;
			up_php_param("arrivalTime", $row[arrivalTime], $type, $tableColumn, $size);
			// BOOKING REFERENCE
			$size = 8;
			up_php_param("bookingReference", $row[bookingReference], $type, $tableColumn, $size);

			// TRAVEL DOCUMENTS
			$size =1;
			up_php_param("travelDocument", $travelID, "file", $tableColumn, $size);
			
			// SAVE BUTTON
			ub_save("", $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
			ub_cancel("$targetPHP?action=view_gig&bandID=$bandID&gigID=$gigID", $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
			up_form_footer();
		}
		else
		{
			// up_table_column($row[transportationType], 1, "Left");
			ubdc_print_transport_type_option_menu($debug, $row[transportationType], $disabled, $tableColumn);
			up_table_column($row[departureFrom], 0, "Left");
			up_table_column($row[destination], 0, "Left");
			up_table_column($row[date], 0, "Left");
			up_table_column($row[departureTime], 0, "center");
			up_table_column($row[arrivalTime], 0, "center");
			// 
			up_table_column($row[bookingReference], 0, "center");
			// TRAVEL DOCUMENTS
			$valign = "center";
			$align = "center";
			if ($row[travelDocument])
			{
				ub_pdf($row[travelDocument], $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
			}
			else
			{
				up_table_column("", 0, "center");
			
				// ub_add("$targetPHP?action=add_travel_document&$bandID=$bandID&gigID=$gigID&travelID=$row[travelID]", 15, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
			}

			// EDIT BUTTON
			ub_edit("$targetPHP?action=edit_travel&bandID=$bandID&gigID=$gigID&travelID=$row[uID]", $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);

		}
		up_new_table_row();		
	}	

	up_table_footer();
}


/*
// ============================================
//	UPDATE - GIG
// ============================================

function ubd_gi_update( $debug, $bandID, $gigID, $date, $time, $place, $placelink, $streetAddress, $city, $rID, $contact, $email, $phone, $company, $oldDate, $oldTime, $eventType, $soundName, $soundPhone, $soundEmail, $soundCompany, $siteName, $sitePhone, $siteEmail, $siteCompany, $map, $wikiMapLink, $travelName, $travelPhone, $travelEmail, $travelCompany )	
{
	$level = 1;
	ubdc_get_band_data($debug, $level, $bandID, $bandName, $bandDB);

	if ($debug) up_note("ubd_gi_update: ENTER", $level);
	// ONLY update "non-null" parameters
	$parameters = "bandID='$bandID' ";
	if ($date != "")
		$parameters = $parameters . ", gigDate='$date'";
	if ($time != "")
		$parameters = $parameters . ", gigTime = '$time'";
	if ($place != "")
		$parameters = $parameters . ", gigAddress = '$place'";
	if ($streetAddress != "")
		$parameters = $parameters . ", streetAddress = '$streetAddress'";
	if ($city != "")
		$parameters = $parameters . ", gigCity='$city'";
	if ($contact != "")
		$parameters = $parameters . ", contactPerson = '$contact'";
	if ($email != "")
		$parameters = $parameters . ",  contactEmail = '$email'";
	if ($phone != "")
		$parameters = $parameters . ", contactPhone = '$phone'";
	if ($company != "")
		$parameters = $parameters . ", contactCompany = '$company'";
	if ($eventType != "")
		$parameters = $parameters . ", eventType='$eventType'";
	if ($eventType != "")
		$parameters = $parameters . ", eventType='$eventType'";
	if ($eventType != "")
		$parameters = $parameters . ", eventType='$eventType'";




	// === SQL query
     	$sqlQuery = "UPDATE $bandDB.gigs  SET $parameters , rID = '$rID', placelink='$placelink', soundName='$soundName', soundPhone='$soundPhone', soundEmail='$soundEmail', soundCompany='$soundCompany', siteName='$siteName', sitePhone='$sitePhone', siteEmail='$siteEmail', siteCompany='$siteCompany', wikiMapLink='$wikiMapLink', travelName='$travelName', travelPhone='$travelPhone', travelEmail='$travelEmail', travelCompany='$travelCompany' WHERE bandID='$bandID' AND gigDate = '$oldDate' AND gigTime = '$oldTime'";
	$result = mysql_query ($sqlQuery);
   	if($result != 1)
   	{
		up_error( "Failed to update the gig on $oldDate\.n");
   	}
}
	if ($debug) up_note("ubd_gi_update: EXIT", $level);
}
*/

?>