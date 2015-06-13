<?php

function ubd_rh_book_rehersal_room($debug)
{
	up_form_header("https://intranet.hiq.se/conferance/addconferance.aspx", "newWin7351");
//	echo "<input type=submit value=\"Boka replokal\"><p></ul>\n</form></td\n</tr>\n\t";
	up_php_param("abc", "Boka HiQ's replokal", "submit");
	up_form_footer();
}

// ============================================
//	GET DATA
// ============================================

function ubd_rh_get_data($debug, $bandID,  $uID, &$date, &$time, &$notes, &$location)
{
	$sql = "SELECT * FROM bands.rehersals WHERE uID='$uID' AND bandID='$bandID'";
	
	$debug = 1;
	if($debug) up_note("sql: $sql");
	$result = mysql_query($sql);
  
	while ($row = mysql_fetch_array ($result))
	{
		$date 		= $row[date];
		$time 		= $row[time];
		$notes 		= $row[note];
		$location 	= $row[location];
	}
	if($debug) up_note ("ubd_rh_get_data($debug, bID:$bandID,  uID:$uID, $date, $time, $notes, $location): EXIT");
}

function ubd_rh_exist($debug, $bandID, $date, $time)
{
	if ($debug) up_note("ubd_rh_exist: ENTER");
	$sql ="SELECT * FROM bands.rehersals WHERE bandID='$bandID' AND date='$date' AND time='$time'";
	$result = mysql_query($sql);
	while(($row = mysql_fetch_array ($result)) != NULL)
	{
		return TRUE;		
	}
	return FALSE;	
}

function ubd_rh_is_rehersal_date($year, $month, $day, $bandID)
{
	$TheDate = "$year-$month-$day";
   	$query = "SELECT * FROM bands.rehersals WHERE date='$TheDate' AND bandID=$bandID"; 
  	$result = mysql_query ($query);

	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		$i++;
	}
	return $i;
}

// ============================================
//	PRINT EDIT/ADD FORM - REHERSAL
// ============================================

// USED for both ADD and EDIT.
function ubd_rh_print_form($debug, $bandID,  $edit, $rehersalID, $date, $time, $notes, $location, $bandMgmtPHP)
{
	if ($debug) up_error("ubd_rh_print_form($debug, $bandID,   $edit, $rehersalID, $date, $time, $notes, $location, PHP:$bandMgmtPHP): ENTER");
	ubdc_print_calendar($debug,  $bandID, 4);
	//$debug = 1;
	if ($debug) up_error("ubd_rh_print_form($debug, $bandID,  $edit, $rehersalID, $date, $time, $notes, $location, PHP:$bandMgmtPHP): ENTER");
	if ($edit)
		ubd_rh_get_data($debug, $bandID,  $rehersalID, &$date, &$time, &$notes, &$location);
	up_uls();
	up_table_header(0);
	up_table_column("");
	up_table_column("Datum", 1);
	up_table_column("", 1);		// Tid
	up_table_column("Notering", 1);
	up_new_table_row();
	$year = date("Y");
	$month = date("m");
	   
	// FORM REHERSAL
	up_form_header($bandMgmtPHP);
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("uID", $rehersalID, "hidden");
	if (!$edit)
	 up_php_param("action", "add_rehersal", "hidden");
	else
	 up_php_param("action", "update_rehersal", "hidden");
	 $bold = 1; $ralign = "right";$valign="center";$tableColumn=1;
	 up_new_table_row();
	 echo "<td>";
	ub_save("", 20, "", "Spara", !$tableColumn);
	ub_cancel("$bandMgmtPHP?action=view_rehersals&bandID=$bandID", 20, "", "Avbryt", !$tableColumn);
	 echo "</td>";
//	up_table_column("Datum:", $bold, $ralign, $hsize, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	$today = date("Y-m-d");
	$start_date = $today;
	$daysBack = 0;
	$daysForward = 90;
	if ($date == "")
	{
		$selectedDate = $today;
	}
	else
	{
		$selectedDate = $date;
	}
	
	uc_print_date_option_menu_from($debug, $start_date, $daysBack, $daysForward, $selectedDate, $tableColumn, $colSpan, "date");
/*
	if ($date == "")
	   echo "<td><input name=date size=10 value=\"$year-$month-\"> </td>\n\t";
	else
	   echo "<td><input name=date size=10 value=\"$date\"> </td>\n\t";
*/	
	if ($time == "") $time = "18:00";
//	up_table_column("Tid:", 1);
	up_php_param("time", $time, "", 1, 6);
	up_php_param("note", $notes, "", 1, 50, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, 5, $tableAlign, $fontSizePx, $unit, $bgcolor, 80, $tableValign, $radioChecked);
//	up_table_column("Notering", 1);
	//	echo "<th align=right>Tid: </th><td><input size=5 name=time value=$time> </td>\n\t";
	up_new_table_row();
//	up_table_column("Notering:", $bold, $ralign, $hsize, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_form_footer();
	up_table_footer();
	// FORM REPLOKAL HiQ
	ubd_rh_print_book_rehersal_room($debug);
	up_ule();
	if ($debug) up_note("ubd_rh_print_form: EXIT");
}

function ubd_rh_calc_future_rehersals($debug, $bandID)
{
	if ($debug) up_note("ubd_rh_calc_future_rehersals($debug, $bandID): ENTER");
	$NofRehersals = 0;
	$today = date("Y-m-d");
	$sql = "SELECT COUNT(bandID) AS NofRehersals FROM bands.rehersals WHERE date>='$today' AND bandID='$bandID'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array ($result);
   
    $NofRehersals = $row[NofRehersals];	
	if($debug) up_note( "ubd_rh_calc_future_rehersals: $NofRehersals = $sql");
	if ($debug) up_note("ubd_rh_calc_future_rehersals($debug, $bandID): RETURNING $NofRehersals");
	return $NofRehersals;
}

function ubd_rh_print_lyrics_row($debug, $i, $lyricsPHP, $table, $title, $artist, $category, $uID, $theKey, $bandID, $PREVIOUS_ACTION)
{
	$tableColumn = 1;
	//up_table_column("$title", 0, $align, $size, $valign, $open, 4, $underline, $bgcolor, $width, $fontsize);
	ub_nice_link("$lyricsPHP?action=view_lyric&songID=$uID&bandID=$bandID&PREVIOUS_ACTION=$PREVIOUS_ACTION", "$title", $target, "Visa $title", $tableColumn);
//	up_table_column($artist, 1, $align, $size, $valign, $open, 4, $underline, $bgcolor, $width, $fontsize);
	up_table_column("($theKey)", 0, $align, $size, $valign, $open, 4, $underline, $bgcolor, $width, $fontsize);
// ub_nice_link($URL, $label, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor)
	
}


function ubd_rh_prints_songs_for_next_rehersal($nextRehersalDate, $bandID, $lyricsPHP, $note, $PREVIOUS_ACTION)
{
	if ($debug) up_note(": ubd_rh_prints_songs_for_next_rehersal($nextRehersalDate, $bandID, $lyricsPHP, $note, $PREVIOUS_ACTION); NYI");
	//ubdc_print_new_songs($debug, $bandID);
	$dbase = "bands";
	$table = "new_lyrics";
	$songStatus = 3; // Next rehersal
	$sql= "SELECT * FROM $dbase.$table, $dbase.lyrics_status WHERE $dbase.$table.uID = lyrics_status.songID AND lyrics_status.songStatus='$songStatus' AND $dbase.$table.bandID='$bandID' AND $dbase.lyrics_status.bandID='$bandID'"; // $dbase.$table.uID, table2.column2
  	$result = mysql_query ($sql);	
//	up_table_header();
	$sameRow = 1;
	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		// Get the Spotify link... 
		$URL = ulin_get_spotify_URL($debug, $row[songID]);		
		// ...and print it.
		if($URL)
		{
			$title	= $row[title];
			$artist	= $row[artist];
			if ($sameRow)
			{
				$colSpan = 0;
				$sameRow = 0;
			}
			else
			{
				$colspan = 6; // Skip < Bin, Pen, Day, Month, Time> columns
				// $colspan = 1;
				up_table_column($text, $bold, $align, $size, $valign, $open, $colspan);
			}
			// uly_print_lyrics_table_row($debug, $i, $lyricsPHP, $table, $title, $artist, $row[category], $row[uID], $row[theKey], $row[bandID]);
			ubd_rh_print_lyrics_row($debug, $i, $lyricsPHP, $table, $title, $artist, $row[category], $row[uID], $row[theKey], $row[bandID], $PREVIOUS_ACTION);
			if ($i == 0)
			{
				if ($debug) 
					up_info("Printing note $note");
				up_table_column($note);
			}
			else
			{
				// up_info("$i. Not Printing note: '$note'");
			
			}
			echo "<br>";
			$i++;
			// up_new_table_row();
		}
		else
		{
			// up_note("$row[songID]: Does not have an URL ($URL).");
		}
	}
//	up_table_footer();
}

// ============================================
//	VIEW - REHERSAL 
// ============================================

function ubd_rh_view($debug, $bandID,  $rehersalID, $bandMgmtPHP, $lyricsPHP, $PREVIOUS_ACTION)	     // If rehersalID == "" => View All
{
	//$debug = 1;
	if ($debug) up_note("ubd_rh_view($debug, $bandID,  $rehersalID): ENTER");

	$today = date ("Y-m-d");
 	$query = "SELECT * FROM bands.rehersals WHERE bandID='$bandID' AND date >= '$today' ORDER BY date";	
 	if($debug) up_note ("sql=$query");
  	$result = mysql_query ($query);
	
	$debug = 0;
	ubdc_print_calendar($debug,  $bandID, 4);
	//$debug = 1;

	if (ubd_rh_calc_future_rehersals($debug, $bandID) == 0)
	{
		up_uls();
		echo "<font size=4>Inga planerade rep.</font>\n";
		ubd_rh_book_rehersal_room($debug);
		up_ule();
		return;
	}
	up_uls();
	up_table_header(0);
	up_table_column("", $bold, $align, $size, $valign, $open, 2, $underline, $bgcolor, $width, $fontsize);
	up_table_column("Datum", 1, $align, $size, $valign, $open, 4, $underline, $bgcolor, $width, $fontsize);
	up_table_column("Nya låtar", 1, $align, $size, $valign, $open, 5, $underline, $bgcolor, $width, $fontsize);
	up_table_column("Noteringar", 1, $align, $size, $valign, $open, 1, $underline, $bgcolor, $width, $fontsize);
	$done = FALSE;
	while ($row = mysql_fetch_array ($result))
	{
		if($debug) echo "$row[date]";
		$day = substr($row[date], 8, 2);
		$month = substr($row[date], 5, 2);
		$monthSwe = uc_get_swedish_month($row[date], $month, 3);
		$year = substr($row[date], 0, 4);
		$weekDay = date("l", mktime(0,0,0, $month, $day, $year));
		
		$veckoDag = uc_get_swedish_weekday($row[date], $year, $month, $day); // uc_get_swedish_weekday($weekDay);
		$niceTime = substr($row[time], 0, 5);

		up_new_table_row();
//$URL, $height, $target, $title, $tableColumn
		ub_delete("$bandMgmtPHP?action=delete_rehersal&date=$row[date]&bandID=$bandID&uID=$row[uID]", 20, "", "Delete rehersal", 1);
		ub_edit("$bandMgmtPHP?action=edit_rehersal&date=$row[date]&bandID=$bandID&uID=$row[uID]", 20, "", "Edit rehersal", 1);
		up_table_column($veckoDag);
		up_table_column($day);
		up_table_column($monthSwe);
		up_table_column("kl.$niceTime");
	//	echo "\t<th align=right valign=top>$veckoDag</th>\n\t<th align=right valign=top>$day</th>\n\t<th align=left valign=top>$monthSwe</th>\n\t<th align=left valign=top>kl.$niceTime</th>\n";
		if (!$done)
		{
			$nextRehersalDate = $year . "-" . $month . "-" . $day;
			ubd_rh_prints_songs_for_next_rehersal($nextRehersalDate, $bandID, $lyricsPHP, $row[note], $PREVIOUS_ACTION);
			// echo "<td valign=top>$row[note]</td>\n";
			//up_table_column($row[note]);
			$done = TRUE;
		}
	    else
		{
			// echo "<td></td><td></td><td></td>\n";
		}
		//if ($newRow) 
		{
			$colspan = 5;
			up_table_column("",  $bold, $align, $hsize, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
			up_table_column($row[note]);
		}
		echo "</tr>\n"; 
		$newRow = 1;
	}	
	up_table_footer();

	up_ule();
//	prints_songs_for_future_rehersals($nextRehersalDate);

	if ($debug) up_note("ubd_rh_view: EXIT");
}

// ============================================
//	ADD - REHERSAL
// ============================================

function ubd_rh_add($debug, $bandID, $date, $time, $note, $location  )
{
	if ($debug) up_note("ubd_rh_add($debug, $bandID, $date, $time, $note, $location  ): ENTER");

	if ($bandID == "" || $date == "" || $time == "")
	{
		up_error("ubd_rh_add: Invalid NULL parameters.");
		return -1;
	}
	if(ubd_rh_exist($debug, $bandID, $date, $time))
	{
	    ubd_rh_print_form($debug, $bandID, 0, $date, $time, $notes, $location);
		up_note("Det finns redan ett rep registrerat $date kl.$time.");
		return;
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
	if ($note)
	{
		$variables = $variables . ", note"; 
		$parameters = $parameters . ", '$note'"; 
	}	
	if ($location)
	{
		$variables = $variables . ", location"; 
		$parameters = $parameters . ", '$location'"; 
	}	
	$uID = ubdc_get_unique_id($debug, "bands", "rehersals", "uID");
	if ($uID)
	{
		$variables = $variables . ", uID"; 
		$parameters = $parameters . ", '$uID'"; 	
	}
    $sql = "INSERT INTO bands.rehersals ( $variables ) VALUES ( $parameters )";
    
    if ($debug) up_note($sql);
    
    $result = mysql_query ($sql);	
      
    if ($result == FALSE)
    {
		up_error("ERROR: Unable to add the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
    else
    {
		ubdc_email_bandmembers($debug, $bandID, "rep", $date, $time);
	}
	if ($debug) up_note("ubd_rh_add: EXIT");
	return 1;	
}

// ============================================
//	UPDATE - REHERSAL
// ============================================

function ubd_rh_update( $debug, $bandID, $uID, $date, $time, $note, $location )	
{
	// $debug = 1;
	if ($debug) up_note("ubd_rh_update( $debug, $bandID, $uID, $date, $time, $note, $location )	: ENTER");

	if ($bandID== ""|| $uID == ""|| $date == ""|| $time == "")
	{
		up_error("ubd_rh_update($debug, $bandID, $uID, $date, $time, $notes, $location):Invalid NULL parameters.");
		return;
	}

	if ($bandID)
	{
		$variables = "bandID='$bandID'"; 
	}
	if ($date)
	{
		$variables = $variables . ", date='$date'"; 
	}
	if ($time)
	{
		$variables = $variables . ", time='$time'"; 
	}
	if ($note)
	{
		$variables = $variables . ", note='$note'"; 
	}	
	if ($location)
	{
		$variables = $variables . ", location='$location'"; 
	}	
	$sql = "UPDATE bands.rehersals SET $variables WHERE uID='$uID'";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_rh_update: Unable to update the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
	if ($debug) up_note("ubd_rh_update: EXIT");
}

// ============================================
//	DELETE - REHERSAL
// ============================================

function ubd_rh_delete($debug, $bandID, $uID)
{
	if ($debug) up_note("ubd_rh_delete: ENTER");
	$sql = "DELETE FROM bands.rehersals WHERE uID='$uID'";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_rh_update: Unable to update the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
	if ($debug) up_note("ubd_rh_delete: EXIT");
}	

function ubd_print_nof_days_option_menu($PHP_with_params, $nofDays, $tableColumn)
{
	if ($tableColumn) echo "<td>\n";
	up_form_header("$PHP_with_params");
	up_select_header($debug, "$PHP_with_params", "nofDays", $identity, 1, $onChangeFunctionName, $tableColumn1, $colSpan, $bgcolor);

	up_select_menu_item($debug, "30 dagar", "30", $disabled, $nofDays);	
	up_select_menu_item($debug, "60 dagar", "60", $disabled, $nofDays);	
	up_select_menu_item($debug, "90 dagar", "90", $disabled, $nofDays);	
	up_select_menu_item($debug, "120 dagar", "120", $disabled, $nofDays);	

	up_select_footer($debug, $tableColumn);
	up_form_footer();
	if ($tableColumn) echo "</td>\n";
}

// ============================================
//	TILLGÄNGLIGHETSSCHEMA
// ============================================

function ubd_rh_band_is_band_available($debug, $bandID, $futureDate)
{
	// === Check GIGS
	$query = "SELECT * FROM bands.gigs WHERE date='$futureDate' AND bandID='$bandID'";
  	$result = mysql_query ($query);	
	while ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_info("Band '$row[bandID]' has a <b>GIG</b> on date: '$futureDate'.");
		return FALSE;
	}
	// === Check REHERSALS
	$query = "SELECT * FROM bands.rehersals WHERE date='$futureDate' AND bandID='$bandID'";
  	$result = mysql_query ($query);	
	while ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_info("Band '$row[bandID]' has a <b>REHERSAL</b> on date: '$futureDate'.");
		return FALSE;
	}
	return TRUE;
}

// Checks whether the given band member is involved in other bands

function ubd_rh_user_in_other_bands($debug, $userID, $bandID)
{
	$query = "SELECT * FROM bands.band_members WHERE userID='$userID' AND bandID!='$bandID'";
  	$result = mysql_query ($query);	
	while ($row = mysql_fetch_array ($result))
	{
		return TRUE;
	}
	return FALSE;
}

// Checks whether the given band member has a gig OR rehersal with another band (than the given one)
// for the given date.
function ubd_rh_other_band_gig_rehersal($debug, $futureDate, $userID, $bandID)
{
	if (ubd_rh_user_in_other_bands($debug, $userID, $bandID) == FALSE)
		return FALSE;
	// GET ALL THE OTHER BANDS given USER is involved with
	$query = "SELECT * FROM bands.band_members WHERE userID='$userID' AND bandID!='$bandID'";
  	$result = mysql_query ($query);	
  	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{
		$bands[$i] = $row[bandID];
		if (!ubd_rh_band_is_band_available($debug, $row[bandID], $futureDate))
		{
			// $debug = 1;
			// if ($debug) up_warning("User $userID is occupied with band '$row[bandID]' on $futureDate.");
			if ($debug) up_note ("User $userID is occupied with band '$row[bandID]' on $futureDate.");
			return TRUE;
		}
	}	
	return FALSE;	
}

function ubd_rh_print_availability_header($debug, $targetPHP, $bandID, $nofDays, &$bandMembers, $userIDedit)
{
	$width=83;
	up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	up_table_column("Datum", 1);
	// PRINT BANDMEMBERS
	$iNofBandMembers = ubd_get_nof_bandmembers($debug, $bandID);
	for ($i=0;$i<$iNofBandMembers;$i++)
	{
		$userID = ubd_get_ith_bandmember($debug, $bandID, $i);
		$bandMembers[$i] = $userID;
		ubd_get_band_member_data($userID, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture);
		// FIXME - Create a nice link where the band member can update his/her availability.
		// ub_nice_link($URL, $label, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor)
		$tableColumn = 1;
		$initials = $firstName[0] . "." . $lastName[0];
		$align = "center";
		ub_nice_link("$targetPHP?action=edit_rehersal_availability&bandID=$bandID&userID=$userID&nofDays=$nofDays", $initials, $target, "Uppdatera tillgänglighet", $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor, $align);
//		ub_nice_link("$targetPHP?action=edit_rehersal_availability&bandID=$bandID&userID=$userID&nofDays=$nofDays", $lastName, $target, "Uppdatera tillgänglighet", $tableColumn);
	}
}

function ubd_rh_print_availability_matrix($debug, $targetPHP, $bandID, $userIDedit, $nofDays, $goto)
{
	//$debug = 1;
	if ($debug) up_info("ubd_rh_print_availability_matrix($debug, $targetPHP, bID:$bandID, uID:$userIDedit, #days:$nofDays, $goto)");
	// $debug = 0;
	if ($nofDays == "")
		$nofDays = 30; // DEFAULT 30 days
//	up_hr();
	$iNofBandMembers = ubd_get_nof_bandmembers($debug, $bandID);
	// up_info( "Band: $bandID has $iNofBandMembers band members.");
	// PRINT HEADER
	up_table_header();
	// up_table_column("<!-- Veckodag -->", 1);
		$tableColumn = 1;
/* IS NOW in the header
	if ($userIDedit != "")
		$PHP_with_params = "$targetPHP?action=edit_rehersal_availability&bandID=$bandID&$userID=$userIDedit";
	else
		$PHP_with_params = "$targetPHP?action=print_rehersal_availability&bandID=$bandID&$userID=$userIDedit";
	ubd_print_nof_days_option_menu($PHP_with_params, $nofDays, $tableColumn);
*/	
	
	ubd_rh_print_availability_header($debug, $targetPHP, $bandID, $nofDays, &$bandMembers, $userIDedit);
/*	
	up_table_column("", 1);
	up_table_column("Datum", 1);
//	up_table_column("Datum", 1);
	// PRINT BAND MEMBERS TITLE

	$iNofBandMembers = ubd_get_nof_bandmembers($debug, $bandID);
	for ($i=0;$i<$iNofBandMembers;$i++)
	{
		$userID = ubd_get_ith_bandmember($debug, $bandID, $i);
		$bandMembers[$i] = $userID;
		ubd_get_band_member_data($userID, &$firstName, &$lastName, &$streetAddress, &$postalCode, &$city, &$phoneWork, &$phoneHome , &$phoneCellular, &$emPrivate, &$emWork, &$picture);
		// FIXME - Create a nice link where the band member can update his/her availability.
		// ub_nice_link($URL, $label, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor)
		$tableColumn = 1;
		$initials = $firstName[0] . "." . $lastName[0];
		ub_nice_link("$targetPHP?action=edit_rehersal_availability&bandID=$bandID&userID=$userID&nofDays=$nofDays", $initials, $target, "Uppdatera tillgänglighet", $tableColumn);
//ub_nice_link("$targetPHP?action=edit_rehersal_availability&bandID=$bandID&userID=$userID&nofDays=$nofDays", $lastName, $target, "Uppdatera tillgänglighet", $tableColumn);
	}

*/

	$tableColumn = 1;
	// PRINT DATUM
	$weekendColor = "#bbbbbb";

	$pos = strpos($StartDay, "0");

	if ($pos != false)
	{
		if ($pos == 0)
		   $StartDay = str_replace("0", " ", $StartDay);
		echo "startDay = $StartDay";			
	}
    if($StartDay[0] == "0")
		$StartDay = $StartDay[1];
	//echo "startDay = $StartDay<br>";	
	$Year = date("Y");		
	$Month  = date("m");
	$date = date("Y-m-d");
	$startDate = $date;
	// ================================================
	// 		DRAW HEADER
	// ================================================
	echo "<tr>\n\t<th align=left><!-- WEEKDAY--></th>\n\t";

	// ================================================
	// 		COMPILE SCHEDULE FOR ALL MEMBERS
	// ================================================
   	$query = "SELECT * FROM bands.rehersal_ability WHERE year=$Year AND month=$Month ORDER BY day"; 
  	$result = mysql_query ($query);

	//  up_info("SQL: $query");
	while ($row = mysql_fetch_array ($result))
  	{	
  		//echo "<li> Found one! -  $Year-$Month-$row[day] - $row[ability]";
  		$day = $row[day];
		$id = ubd_rh_get_column($bandID, $row[userID]);		
  		// echo "<li> Found one! -  $Year-$Month-$row[day] - $row[ability] - userID:$row[userID] - column:$id";
		$AbilityMatrix[$id][$day] = $row[ability];
	}	
	$monthName 		= uc_get_swedish_month($date, $month, $nofChars);
    $nofBandmembers = $iNofBandMembers;
	// ================================================
	// 		PRINT THE SCHEDULE
	// ================================================
	$ColumnWidth = 70;
	$ColumnWidthDate = 110;

	$StartDay 	= date("d");
	$EndDay 	= $StartDay+$nofDays;

	$gigColor 		= "55ff55"; // GREEN
	$rehersalColor 	= "ffff55"; // YELLOW
	
	//up_info( "EndDay = $EndDay");
	//echo "EndDay = $EndDay";
	for($i=$StartDay; $i <= $EndDay; $i++)
	{
		$daysInFuture = $i-$StartDay;
	    uc_get_future_date($debug, $startDate, $daysInFuture, &$futureWeekday, &$futureDay, &$futureMonth, &$futureYear, &$futureDate);
	
		if ($futureDate == $goto)
		{
			up_table_footer();
			echo "<p id=$goto></p>";
			up_table_header();
			ubd_rh_print_availability_header($debug, $targetPHP, $bandID);
		}
	
		if ($futureMonth != $Month)
		{
			// Update Availability Matrix
   			$query = "SELECT * FROM bands.rehersal_ability WHERE year=$futureYear AND month=$futureMonth ORDER BY day"; 
  			$result = mysql_query ($query);

			// RESET AbilityMatrix
			for($id = 0; $id < 30;$id++)
			{
				for($rDay=1; $rDay <=31; $rDay++)
					$AbilityMatrix[$id][$rDay] = "";
			}
			// RESET AbilityMatrix
			while ($row = mysql_fetch_array ($result))
  			{	
 				$id = ubd_rh_get_column($bandID, $row[userID]);		
 				if ($debug) up_info ("Found one! uID:$row[userID] col:$id-  $futureYear-$futureMonth-$row[day] - $row[ability]");
  				$day = $row[day];
  				// echo "<li> Found one! -  $Year-$Month-$row[day] - $row[ability] - userID:$row[userID] - column:$id";
				$AbilityMatrix[$id][$day] = $row[ability];
			}	
			$Month = $futureMonth;
		}
	
	   if ( ubd_rh_is_rehersal_date($futureYear, $futureMonth, $futureDay, $bandID))
	   {
			$bgcolor = $rehersalColor;
	   }
	   else if ( ubd_gi_is_gig_date($futureYear, $futureMonth, $futureDay, $bandID))
	   {
			$bgcolor = $gigColor;
	   }
	   else if ( uc_is_weekend($futureWeekday ) || uc_is_public_holiday($debug, $futureYear, $futureMonth, $futureDay))
	   {	   
			$bgcolor = "#dddddd";
	   }
	   else // WEEK-DAY
	   {
			$bgcolor = "#eeeeff";		
	   }
	   // ===========
	   //	WEEK DAY
	   // ===========
	   $nofChars = 2;
	   $shortWeekDay = uc_get_swedish_weekday($futureDate, $year, $month, $day, $nofChars);
//	   echo "<tr>\n\t<th align=right bgcolor=$bgcolor>$shortWeekDay</th>";
	   up_new_table_row();
	   echo "\n\t<th align=right bgcolor=$bgcolor>$futureWeekday</th>";
	   // ===========
	   //	DATE
	   // ===========
	   $nofChars = 3;
		$monthName = uc_get_swedish_month("", $futureMonth, $nofChars);
	   echo "<th width=$ColumnWidthDate bgcolor=$bgcolor align=right>$futureDay $monthName</th>\n\t";
	   for($j=0; $j < $nofBandmembers; $j++)
	   {
			$Ability = $AbilityMatrix[$j][$futureDay];
	   		if ($userIDedit == $bandMembers[$j])
	   		{
	   			// Print combo box
	   			// ubd_rh_print_availability_combo($bandID, $userID, $Year, $Month, $i, $Ability, $tableColumn);
	   			ubd_rh_print_availability_buttons($targetPHP, $bandID, $userIDedit, $futureYear, $futureMonth, $futureDay, $Ability, $tableColumn, $nofDays);
	   		}
	   		else 
	   		{
	   			if (ubd_rh_other_band_gig_rehersal($debug, $futureDate, $bandMembers[$j], $bandID))
	   			{
	   				// echo "Do we get here?";
	   				$Ability = "Kan inte";
	   				$bgBefore = $bgcolor;
	   				$bgcolor = "ff0000";
	   			}
				ubd_rh_print_availability_row($Weekday[$i], $Ability, $ColumnWidth, $bgcolor);
				//$bgcolor = $bgBefore;
			}

  	   }  // ALL USERS
    	if (($gigID = ubd_gi_is_gig_date($futureYear, $futureMonth, $futureDay, $bandID) ) > 0)
	   {
	   		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID);
	   		$city = "";
	   		ubdc_get_arena_data($debug, $dbase, $arenaID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL);
			echo "<td align=left width=$ColumnWidth bgcolor=ffffff >$city</td>\n\t";
	   }
        else if (ubd_rh_is_rehersal_date($futureYear, $futureMonth, $futureDay, $bandID))
	   {
			echo "<td align=left width=$ColumnWidth bgcolor=ffffff >Rep</td>\n\t";
	   }
        else if (uc_is_public_holiday($debug, $futureYear, $futureMonth, $futureDay))
	   {
			$holiday = uc_get_public_holiday($futureYear, $futureMonth, $futureDay);
			echo "<td align=left  bgcolor=ffffff >$holiday</td>\n\t";
	   }
	   else
	   {
	   		if ($userIDedit == "")
	   			ub_check("$targetPGP?action=add_rehersal&bandID=$bandID&date=$futureDate&time=18:00", 20, $target, "Lägg till rep", $tableColumn);
	   }
	   up_new_table_row();
	}	
	
	// END OF TABLE
	up_table_footer();
}

function ubd_rh_get_column($bandID, $userID)
{
//	up_info("ubd_rh_get_column(bID:$bandID, uID:$userID)");
   	$query = "SELECT * FROM bands.band_members  WHERE bandID='$bandID' ORDER BY userID"; 
  	$result = mysql_query ($query);

	$iThUser = 0;
	while ($row = mysql_fetch_array ($result))
  	{
  		// echo "<li> if ($userID == $row[userID])";
		if ($userID == $row[userID])
		   return $iThUser;
		$iThUser++;
	}			
//	up_warning("ubd_rh_get_column($bandID, $userID)_ NOT FOUND");
}

function 	ubd_rh_print_availability_row($Weekday, $Ability, $ColumnWidth, $bgcolor)
{
//	if ($Ability != "")  echo "<li> ubd_rh_print_availability_row($Weekday, $Ability)";
		if ( uc_is_weekend($Weekday ))
		{
			//$bgcolor = "#dddddd";
			if ($Ability== "Kan")
			{
				$weekendColor = "#00aa00";
				echo "<th align=left width=\"$ColumnWidth\" bgcolor=$weekendColor >$Ability</th>";
			}
			else if ($Ability== "Kanske")
			{
				$weekendColor = "#cccc00";
				echo "<th align=left width=\"$ColumnWidth\" bgcolor=$weekendColor >$Ability</th>";
			}
			else if ($Ability== "Kan inte")
			{
				$weekendColor = "#ffaaaa";
				echo "<th align=left width=\"$ColumnWidth\" bgcolor=$weekendColor ></th>\n\t"; // $Ability
			}
			else
			{
				$weekendColor = $bgcolor;
				echo "<td align=left width=\"$ColumnWidth\" bgcolor=$weekendColor > $Ability</td>";
			}
		}
		else  // VARDAG
		{
			//$bgcolor = "#eeeeff";
			if ($Ability== "Kan")
			{
				$weekdayColor = "#00dd00";
				echo "<th align=left width=\"$ColumnWidth\" bgcolor=$weekdayColor >$Ability</th>\n\t";
			}
			else if ($Ability== "Kanske")
			{
				$weekdayColor = "#dddd00";
				echo "<th align=left width=\"$ColumnWidth\" bgcolor=$weekdayColor >$Ability</th>\n\t";
			}
			else if ($Ability== "Kan inte")
			{
				$weekdayColor = "#ffaaaa";
				echo "<th align=left width=\"$ColumnWidth\" bgcolor=$weekdayColor ></th>\n\t"; // $Ability
			}
			else
			{
				$weekdayColor = $bgcolor;
				echo "<td align=left bgcolor=$weekdayColor width=\"$ColumnWidth\" >$Ability</td>\n\t";
			}
		}
}

function	ubd_rh_print_availability_combo($bandID, $userID, $Year, $Month, $day, $AbleToReherse, $tableColumn)
{
	if ($tableColumn)
		echo "<th>";
	echo "<select name=ability[]>\n\t\t";

	if ($AbleToReherse == "Kan")
		echo "<option value=Kan selected> Kan\n\t\t";
	else
		echo "<option value=Kan> Kan\n\t\t";
	if ($AbleToReherse == "Kan inte" || $AbleToReherse == "")
		echo "<option value=\"Kan inte\" selected> Kan inte\n\t";
	else
		echo "<option value=\"Kan inte\"> Kan inte\n\t";
	if ($AbleToReherse == "Kanske")
		echo "<option value=\"Kanske\" selected> Kanske\n\t";
	else
		echo "<option value=\"Kanske\"> Kanske\n\t";

	echo "</select>\n\t";
	
	if ($tableColumn)
		echo "</th>";
}

function	ubd_rh_print_availability_buttons($targetPHP, $bandID, $userID, $Year, $Month, $day, $AbleToReherse, $tableColumn, $nofDays)
{
	if ($AbleToReherse == "Kan")
		$bgcolor="#00ff00"; // GREEN
	else if ($AbleToReherse == "Kan inte")
		$bgcolor="#ff0000"; // RED
	else 
		$bgcolor="#eeeeff"; // BLUE
	if ($tableColumn)
		echo "<th bgcolor=$bgcolor>";
		if ($day <= 9 && strlen($day) == 1)
			$day = "0" . $day;
	$date = "$Year-$Month-$day";
	// ub_check($URL, $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor)
	ub_check("$targetPHP?action=add_availability&bandID=$bandID&userID=$userID&year=$Year&month=$Month&day=$day&status=yes&nofDays=$nofDays&goto=$date#$date", 15, $target, "Kan");
	echo "&nbsp ";
	ub_cancel("$targetPHP?action=add_availability&bandID=$bandID&userID=$userID&year=$Year&month=$Month&day=$day&status=no&nofDays=$nofDays&goto=$date#$date", 15, $target, "Kan inte");
	
	if ($tableColumn)
		echo "</th>";
}

function udb_rh_availability_exist(	$debug, $userID, $year, $month, $day)
{
	if($debug) up_info("udb_rh_availability_exist($debug, $userID, $year, $month, $day, $status)");
	
    $query = "SELECT * FROM bands.rehersal_ability WHERE year='$year' AND month='$month' AND day='$day' AND userID='$userID'";
        
    $result = mysql_query ($query);	

	while($row = mysql_fetch_array ($result))
	{
		if($debug) up_info("udb_rh_availability_exist($debug, $userID, $year, $month, $day, $status) . TRUE");
		return TRUE;
	}
	if($debug) up_info("udb_rh_availability_exist($debug, $userID, $year, $month, $day, $status) . FALSE");
	return FALSE;    
}

function  ubd_rh_update_availability($debug, $userID, $year, $month, $day, $status)
{
	// up_warning("update_availability: Not yet implemented");
	
	if ($status == "no")
		$ability = "Kan inte";
	else if ($status == "yes")
		$ability = "Kan";
	else
		$ability = "";
	
	$query = "UPDATE bands.rehersal_ability SET ability='$ability' WHERE userID='$userID' AND year='$year' AND month='$month' AND day='$day'"; 
     $result = mysql_query ($query);
   	if($result != 1)
   	{
		up_error( "ubd_rh_update_availability: Failed to update availability.");
		$msg = mysql_error();
		up_error( "mysql_error: $msg");
   	}
}

function  ubd_rh_add_availability($debug, $bandID, $userID, $year, $month, $day, $status)
{
	// $debug = 1;
	
	if($debug) up_info("ubd_rh_add_availability($debug, $bandID, $userID, $year, $month, $day, $status)");
	if (udb_rh_availability_exist(	$debug, $userID, $year, $month, $day))
	{
		ubd_rh_update_availability($debug, $userID, $year, $month, $day, $status);
		return;
	}
	
	if ($bandID == "" || $userID == "" || $year == "" || $month == ""|| $day == "" || $status == "")
	{
		up_error("ubd_rh_add_availability($debug, $bandID, $userID, $year, $month, $day, $status) : INVALID NULL parameter(s).");
		return;
	}
	
	if ($userID)
	{
		$variables = "userID"; 
		$parameters = "'$userID'"; 
	}
	if ($year)
	{
		$variables = $variables . ", year"; 
		$parameters = $parameters . ", '$year'"; 
	}
	if ($month)
	{
		$variables = $variables . ", month"; 
		$parameters = $parameters . ", '$month'"; 
	}
	if ($day)
	{
		$variables = $variables . ", day"; 
		$parameters = $parameters . ", '$day'"; 
	}
	if ($status)
	{
		$variables = $variables . ", ability"; 
		if ($status == "no")
			$parameters = $parameters . ", 'Kan inte'"; 
		else if ($status == "yes")
			$parameters = $parameters . ", 'Kan'"; 
	}	
    $sql = "INSERT INTO bands.rehersal_ability ( $variables ) VALUES ( $parameters )";
    
    if ($debug) up_note($sql);
    
    $result = mysql_query ($sql);	
      
    if ($result == FALSE)
    {
		up_error("ERROR: Unable to add the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
/**/
}

?>