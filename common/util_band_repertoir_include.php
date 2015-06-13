<?php


function ubd_rp_get_repertoir_data($debug, $repertoirID, &$name, &$nofSets, &$long_pdf, &$lastUpdateDate,  &$lastUpdateTime, &$short_pdf)
{
	$query = "SELECT * FROM bands.repertoir WHERE repertoirID = '$repertoirID'";
 	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result))
	{	
		$name 		=  $row[name];
		$nofSets 	=  $row[nofSets];
		$long_pdf 		=  $row[pdf];
		$short_pdf 		=  $row[short_pdf];
		$lastUpdateDate = $row[lastUpdate];
		$lastUpdateTime = $row[lastUpdateTime];
	}
	if ($debug) up_info("ubd_rp_get_repertoir_data($debug, $repertoirID, $name, #sets:$nofSets, pdf:$pdf, last date:$lastUpdateDate,  last time:$lastUpdateTime): EXIT");
}

function ubd_rp_print_repertoir_option_menu($debug, $bandID, $selectedRepertoir, $disabled, $tableColumn)
{
	$name = "repertoirID";
	up_select_header($debug, $targetPhp, $name, $identity, $callItself, $onChangeFunctionName, $tableColumn, $colSpan, $bgcolor, $tableAlign);
	up_select_menu_item($debug, "V�lj repertoir", -1, $disabled, $selectedRepertoir);

	$query 	= "SELECT * FROM bands.repertoir WHERE bandID='$bandID' AND hidden='0' ORDER BY repertoirID DESC";
	
 	$result = mysql_query ($query);
	
  	while ($row = mysql_fetch_array ($result))
	{				
		up_select_menu_item($debug, $row[name], $row[repertoirID], $disabled, $selectedRepertoir);
	}
	up_select_footer();
}

function ubd_rp_calc_repertoirs($debug, $bandID)
{
	if ($debug) up_note("ubd_rp_calc_repertoirs($debug, $bandID): ENTER");
	$NofRehersals = 0;
	$today = date("Y-m-d");
	$sql = "SELECT COUNT(bandID) AS NofRepertoirs FROM bands.repertoir WHERE bandID='$bandID' AND hidden='0'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array ($result);
   
    $NofRepertoirs = $row[NofRepertoirs];	
	if($debug) up_note( "ubd_rp_calc_repertoirs: $NofRepertoirs = $sql");
	if ($debug) up_note("ubd_rp_calc_repertoirs($debug, $bandID): RETURNING $NofRepertoirs");
	return $NofRepertoirs;
}

function  ubd_rp_UpdateLastUpdate($rID)
{
   $today = date("Y-m-d");
   $Thistime  = date("H:i:s");

   $query = "UPDATE bands.repertoir SET lastUpdate='$today', lastUpdateTime='$Thistime' WHERE repertoirID='$rID'";
   $result = mysql_query ($query);
   if ($result == FALSE)
   {
     up_error( "ubd_rp_UpdateLastUpdate($rID): Failed to update last update rID = '$rID' AND  lastUpdate='$today' lastUpdateTime='$Thistime'\n<br>");	
	$msg = mysql_error();
	if ($msg)
		up_error($msg);
   }
}
// ============================================
//			REPERTOIR SONGS
// ============================================

function ubd_rp_song_already_selected($debug, $songID, $rID, $bandID)
{
	if ($debug) up_note("ubd_rp_song_already_selected($debug, bID:$bandID, rID:$rID, song:$songID): ENTER", 3);
	$query = "SELECT * FROM repertoir_songs WHERE repertoirID='$rID' AND songID='$songID' AND bandID='$bandID'";
 	$result = mysql_query ($query);

  	while ($row = mysql_fetch_array ($result))
	{	  		
		return TRUE;
	}
	return FALSE;
}

function ubd_rp_add_repertoir_song($debug, $bandID, $rID, $sID, $songID, $comment)
{
	if ($debug) up_note("ubd_rp_add_repertoir_song($debug, bID:$bandID, rID:$rID, set:$sID, song:$songID, cmt:$comment): ENTER", 2);
	
	if($bandID == "" ||  $rID== "" ||  $sID== "" ||  $songID== "")
	{
		up_error("ubd_rp_add_repertoir_song: Invalid NULL parameters.<br>bID='$bandID' ||  rID='$rID' ||  setID='$sID' ||  songID='$songID'");
		return;
	}
	
   	if (!ubd_rp_song_already_selected($debug, $songID, $rID, $bandID))  
   	{	
      	$orderNo = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $sID)+1;
      	
 		if ($bandID)
		{
			$variables = "bandID"; 
			$parameters = "'$bandID'"; 
		}
		if ($rID)
		{
			$variables = $variables . ", repertoirID"; 
			$parameters = $parameters . ", '$rID'"; 
		}
		if ($sID)
		{
			$variables = $variables . ", setNo"; 
			$parameters = $parameters . ", '$sID'"; 
		}
	//	if ($orderNo)
	//	{
	//	}	
		if ($songID)
		{
			$variables = $variables . ", songID"; 
			$parameters = $parameters . ", '$songID'"; 
		}	     	
		$variables = $variables . ", orderNo"; 
		$parameters = $parameters . ", '$orderNo'"; 
      	
      	$sql = "INSERT INTO bands.repertoir_songs ( $variables ) VALUES ( $parameters )";
      	
      	if($debug) up_note("SQL=$sql");
      	
      	$result = mysql_query ($sql);
 
      	if ($result != 1)
      	{
	 		up_error ("ubd_rp_add_repertoir_song: Failed to insert the song ($title) in the repertoir $rID set:$sID.");
	 		$msg = mysql_error();
	 		if($msg)
	 			up_error ($msg);
      	}	
      	else
      	{
      		if($debug) up_note("ubd_rp_add_repertoir_song: Added song",2);
      		ubd_rp_UpdateLastUpdate($rID);
      	}
    }
    else
    {
      		if($debug) up_note("ubd_rp_add_repertoir_song: Song already selected", 2);    
    }
    //UpdateLastUpdate($rID);
}

function ubd_rp_remove_repertoir_song($debug, $bandID, $rID, $sID, $iThSong)
{
// ubd_rp_remove_repertoir_song($debug, $bandID, $rID, $sID)

	$iNofSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $sID);

    $query = "DELETE FROM Bands.repertoir_songs WHERE  repertoirID = '$rID' AND setNo = '$sID' AND orderNo = '$iThSong'";

 	$result = mysql_query ($query);

	// Update the order number of succeding songs
	if ($debug) echo "<li> Enter loop: for (i = $iThSong+1; i <= $iNofSongs (nofsongs); $i++)";
	for ($i = $iThSong+1; $i <= $iNofSongs; $i++)
	{
		if ($debug) echo "<li> Updating song $i\n<br>";
		$iMinusOne = $i - 1;
		//echo "UPDATE repertoir_songs SET orderNo='$iMinusOne' WHERE repertoirID = '$rID' AND setNo = '$sID' AND orderNo = '$i'\n<br>";
		$query = "UPDATE Bands.repertoir_songs SET orderNo='$iMinusOne' WHERE orderNo='$i' AND repertoirID='$rID' AND setNo='$sID' ";

		$result = mysql_query ($query);

		if ($result != -1)
		{
		   up_error( "ubd_rp_remove_repertoir_song: Failed to update song rID = '$rID' AND setNo = '$sID' AND orderNo = '$i\n<br>");
		}
	}
	 ubd_rp_UpdateLastUpdate($rID);
}

function ubd_rp_get_repertoir_name($debug, $bandID, $rID)
{
	$query = "SELECT * FROM bands.repertoir WHERE repertoirID = '$rID'";
 	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result))
	{	
		return $row[name];
	}
	return "repertoir_name : not found";
}

function ubd_rp_get_last_update($debug, $bandID, $rID)
{
	$query = "SELECT * FROM bands.repertoir WHERE repertoirID = '$rID'";
 	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result))
	{	
		return $row[lastUpdate] . ", " . $row[lastUpdateTime];
	}
	return "repertoir_name : not found";
}

function ubd_rp_print_form($debug, $bandID, $edit, $rehersalID, $name,  $nofSets, $errorIndication, $bandMgmtPHP)
{
	if ($debug) up_note("ubd_rp_print_form(dbug:$debug, bID:$bandID, edit:$edit, rID:$rehersalID, name:$name,  #sets:$nofSets, eInd:$errorIndication, php:$bandMgmtPHP): ENTER");
	if($edit)
	{
		ubd_edit_repertoir_songs($debug, $bandID, $rehersalID, $bandMgmtPHP);
	}
	else	// Add new repertoir
	{
		up_uls();
		up_form_header("$bandMgmtPHP");
		up_table_header(0);
		up_php_param("action", "add_repertoir", "hidden");	
		up_php_param("bandID", $bandID, "hidden");	
		$align = "right";
		$valign = "baseline";
		$valign = "bottom//";
		//$errorIndication = 0;
		// NAMN
		up_table_column("Namn:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);	
		if ($errorIndication && $name == "")
			up_php_param("name", "$name", "", 1, 25, $errorIndication);	
		else if ($errorIndication && $name != "")
			up_php_param("name", "$name", "", 1, 25, !$errorIndication);	
		else
			up_php_param("name", "$name", "", 1, 25, $errorIndication);	
		up_new_table_row();
		// Antal set
// up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)
		up_table_column("Antal set:", 1, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize);	
		if ($errorIndication && $nofSets == "")
			up_php_param("nofSets", "$nofSets", "", 1, 1, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, 1, $tableValign, $radioChecked);	
		else if ($errorIndication && $nofSets != "" && $nofSets > 0)
			up_php_param("nofSets", "$nofSets", "", 1, 1, !$errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, 1, $tableValign, $radioChecked);	
		else
			up_php_param("nofSets", "$nofSets", "", 1, 1, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, 1, $tableValign, $radioChecked);	
		
		up_new_table_row();
// $URL, $height, $target, $title, $tableColumn
		up_table_column("");
		ub_save($URL, $height, $target, $title, 1, 1);
		ub_cancel("$bandMgmtPHP?action=view_repertoirs&bandID=$bandID", $height, $target, $title, 1);
		echo "</td>\n";
		up_table_footer();
		up_form_footer();
		up_ule();
	}

	if ($debug) up_note("ubd_rp_print_form: EXIT");
}

// Auxiliary function to ubd_rp_printSetSong used when editing song lists

function ubd_rp_printOrderNoOptionMenu($bandID, $IthSong, $sID, $rID, $songID, $bandMgmtPHP)
{
	// $debug = 1;
	
	if($debug) up_note( "ubd_rp_printOrderNoOptionMenu($bandID, $IthSong, $sID, $rID, $songID, $bandMgmt) : ENTER");
	$nofSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $sID);

	echo "<td align=left valign=top>";
	
	// <form action=\"$bandMgmtPHP\"  enctype=\"multipart/form-data\"  method=post>\n";
	
	up_form_header("$bandMgmtPHP");
	
	up_php_param("action", "place_song_in_repertoir", "hidden");
	up_php_param("subaction", "order_change", "hidden");
	up_php_param("bandID", "$bandID", "hidden");
	up_php_param("rID", "$rID", "hidden");
	up_php_param("sID", "$sID", "hidden");
	up_php_param("iThSong", "$IthSong", "hidden");
	up_php_param("songID", "$songID", "hidden");

/* BEFORE
	echo "<input type=\"hidden\" name=\"action\" value=\"place_song_in_repertoir\" />\n"; // UPDATE SONG 
	echo "<input type=\"hidden\" name=\"bandID\" value=\"$bandID\" />\n"; //  
	echo "<input type=\"hidden\" name=\"rID\" value=\"$rID\" />\n"; //  
	echo "<input type=\"hidden\" name=\"sID\" value=\"$sID\" />\n"; //  
	echo "<input type=\"hidden\" name=\"iThSong\" value=\"$IthSong\" />\n"; //  
	echo "<input type=\"hidden\" name=\"subaction\" value=\"order_change\" />\n"; // UPDATE SONG (CHANGE ORDER)
*/
	echo "\t<select name=newPosition onchange=this.form.submit()>\n\t";
	
	for($i = 1; $i <= $nofSongs; $i++)
	{
		if($i == $IthSong)
		{
			echo "<option value=$i selected>$i\n";
		}
		else
		{
			echo "<option value=$i>$i\n";
		}
	}
	// echo "<option value=-1>Ta bort\n";

	echo "</select>";
	
	ub_delete("$bandMgmtPHP?action=delete_song_from_set&bandID=$bandID&rID=$rID&sID=$sID&iThSong=$IthSong");
	// <a href=repertoir.php?action=4&rID=$rID&sID=$sID&iThSong=$IthSong style=\"text-decoration: none;\">
	//<img src=../../images/icons/button_delete.png border=0 height=20></a></td>
	// </form>\n";
	up_form_footer();
	if($debug) up_note( "ubd_rp_printOrderNoOptionMenu($IthSong, $sID, $rID) : EXIT");
}


// AUXILIARY METHOD TO print_song_row used when editing song lists
//
//	Auxiliary functions:
//		print_setID_GUI
//		printSetSong

function ubd_rp_printSetSong($bandID, $iSet, $IthSong, $rID, $bandMgmtPHP)
{
	// $debug = 1;
	// $debug = 1;
	if ($debug) up_note("ubd_rp_printSetSong($bandID, $iSet, $IthSong, $rID, $bandMgmtPHP)");
	if ($iSet == 4 && $IthSong == 3)
		if($debug) up_note( "ubd_rp_printSetSong(bID:$bandID, set:$iSet, song/row:$IthSong, rID:$rID, $bandMgmtPHP) : ENTER");
	$debug = 0;
	$query = "SELECT * FROM bands.repertoir_songs WHERE orderNo = '$IthSong' AND setNo = '$iSet' AND repertoirID ='$rID'";
 	$result = mysql_query ($query);

	switch($iSet)
	{
	case -1;	
		$bgcolors[1] = "#ff4444"; // RED
		break;
	case 1;	
		$bgcolors[1] = "#ffeeee"; // RED
		break;
	case 2:
		$bgcolors[2] = "#ffffee"; // YELLOW
		break;
	case 3:
		$bgcolors[3] = "#eeffee"; // GREEN
		//$bgcolor = "bgcolor=$bgcolors[3]";
		break;
	case 4:
		$bgcolors[4] = "#eeeeff"; // BLUE
		//$bgcolor = "bgcolor=$bgcolors[4]";
	}
	$i = 0;
	while ($row = mysql_fetch_array ($result))
	{	
		// TITLE
		if ($row[songID] != "")
		{
		//	echo "<li> ubd_rp_printSetSong1234";
			   uly_get_lyrics_data($debug, "bands", "new_lyrics", $row[songID], $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize);
		}
		// AFTER
		$maxSongPreviousSet = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $iSet-1);
		// BEFORE
		// $maxSongPreviousSet = ubd_rp_GetMaxNofSongsPerSet($debug, $rID, $sID-1);

		// $maxSongPreviousSet = ubd_rp_GetMaxNofSongsPerSet($debug, $bandID, $rID, $iSet-1);
		if ($iSet == 4 && $IthSong == 3)
		if($debug)  up_note( "ubd_rp_printSetSong: if ($iSet> 1 && $IthSong > $maxSongPreviousSet)  ");
		if ($iSet> 1 && $IthSong> $maxSongPreviousSet  )
		{
			echo "<td></td><td></td>";
		}
		else
		{
			// up_note( "ubd_rp_printSetSong: if (set:'$iSet'> 1 && song:$IthSong > $maxSongPreviousSet (max:prev set)  ");
			// echo "<td></td><td></td>";
		}
		ubd_rp_printOrderNoOptionMenu($bandID,$IthSong, $iSet, $rID, $row[songID], $bandMgmtPHP);
		if ($manuscriptID = ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $iSet, $IthSong))
		{
			
			echo "<td valign=top $bgcolor><u>$title</u>";
			//echo "<a href=$bandMgmtPHP?action=delete_manuscript&bandID=$bandID&rID=$rID&uID=$manuscriptID title=\"Delete this manuscript.\">";
//echo "<img src=../../images/icons/delete_icon.jpg height=20 border=0></a>";
			ub_delete("$bandMgmtPHP?action=delete_manuscript&bandID=$bandID&rID=$rID&uID=$manuscriptID", 20, "", "Ta bort mellansnack efter denna l�t.");
			//echo "delete manuscript</a>";
			echo "</td>\n\t";
		}
		else // if manuscript does not exist
		{

			echo "<td valign=baseline $bgcolor>$title";
			// Print + button
			ub_add("$bandMgmtPHP?action=add_manuscript&bandID=$bandID&rID=$rID&iSet=$iSet&iThSong=$IthSong", 20, "", "L�gg till mellansnack efter denna l�t.", "", "center");
			echo "</td>\n\t";
		}
	}
//	if ($i == 0) //ie. no song
//		echo "<td></td><td valign=top></td>\n\t";
	if($debug) up_note( "ubd_rp_printSetSong($bandID, $iSet, $IthSong, $rID, $bandMgmtPHP) : EXIT");
}

function ubd_rp_print_setID_GUI($nofSets)
{
	echo "\t<select name=sID onchange=this.form.submit()>\n\t";
	echo "<option value=0 selected>V�lj set\n";

	for($i = 1; $i <= $nofSets; $i++)
	{
		echo "<option value=$i>$i\n";
	}
	echo "<option value=-1>Extra\n";  
	echo "</select>"; //  </form>";
	up_form_footer();
}

// ================================ ubd_rp_print_song_row
// Description: Used to when edit to print BOTH available song $songID and the I'th song in the sets [1, $nofSets], on the SAME row.
// Auxilliary methods
//		printSetSong
//
// Aux. to ubd_edit_repertoir_songs.

function ubd_rp_print_song_row($debug, $bandID, $songID, $rID, $nofSets, $IthSong, $bandMgmtPHP)
{
	//  $debug = 1;
	if($debug) up_note("ubd_rp_print_song_row(dbug:$debug, bID:$bandID, songID:$songID, rID:$rID, nofSets:$nofSets, IthSong:$IthSong, PHP: $bandMgmtPHP: ENTER");
	
	up_new_table_row();
	// SET ID
	echo "<td align=left valign=top width=70>\n";
	up_form_header("$bandMgmtPHP");
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("songID", $songID, "hidden");
	up_php_param("rID", $rID, "hidden");
	up_php_param("action", "add_song_to_set", "hidden");
	ubd_rp_print_setID_GUI($nofSets);
	echo "</td>";
	// TITLE
	if ($songID != "")
	{
		// echo "<li> ubd_rp_printSetSong";
		uly_get_lyrics_data($debug, "bands", "new_lyrics", $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize);
		up_table_column("$IthSong. $title");
	}
	else
	{
		up_table_column("");
	}
	$NofSongsInSet1 = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, 1);
	if ($nofSets >= 1)
	{
	   	for($iThSet=1; $iThSet<=$nofSets;$iThSet++)
	  	{
	    	if ($iThSet == 1 && $IthSong > $NofSongsInSet1)
	    	{
	    		//up_info("Print EXTRA SONGS");
	    		// Print EXTRA SONGS
	    		if ($IthSong == $NofSongsInSet1 +1)
	    		{
	    			// PRINT LABEL
	    			up_table_column("<hr>EXTRA<hr>");
	    		}
	    		else
	    		{
	    			$IthExtraSong = $IthSong - $NofSongsInSet1 -1;
	    			ubd_rp_printSetSong($bandID, -1, $IthExtraSong, $rID, $bandMgmtPHP);	
	    		}
	    	}
	    	else
	    	{
	    		//up_info("Print EXTRA SONGS: if ($iThSet == 1 && $IthSong > $NofSongsInSet1)");
	    		ubd_rp_printSetSong($bandID, $iThSet, $IthSong, $rID, $bandMgmtPHP);	
	    	}
	  	}
	}
	echo "\n</tr>\n";
	if($debug) up_note("ubd_rp_print_song_row(artist:$artist, title:$title, rID:$rID, nofSets:$nofSets, IthSong:$IthSong, PHP:$bandMgmtPHP) : EXIT");
}

function ubd_rp_print_extra_songs($debug, $bandID, $rID, $bandMgmtPHP)
{
	if ($bandID == "" || $rID == "" )
	{
		up_error("ubd_rp_print_extra_songs: Illegal NULL parameters: (bID:'$bandID', riD:'$rID'));");
		return;
	}
	if ($debug) up_info("print_extra_songs_inside");
	$query = "SELECT * FROM bands.repertoir_songs WHERE repertoirID = '$rID' AND setNo = '-1'";
 	$result = mysql_query ($query);
	
	echo "<h3>Extranummer</h3>";
	echo "<ul><table border=0>\n";
  	while ($row = mysql_fetch_array ($result))
	{
		echo "<tr><td>";
		$tempTitle = str_replace(" ", "+", $row[title]); // Replace whitespaces with "+"
		// TA BORT REPERTOIR KNAPP
	       ub_delete("$bandMgmtPHP?action=4&rID=$rID&RemoveTitle=$tempTitle&sID=-1&iThSong=-1", 25, "", "Ta bort extra l�t");
		echo "\n\t$row[title]\n";
		echo "</td></tr>\n";
	}
	echo "</table></ul>\n";
}

function ubd_rp_print_extra_songs2($debug, $bandID, $rID, $userID)
{
	// ==================	
	// EXTRA SONGS
	// ==================	
	// up_info("PRINTING EXTRA SONGS");
		   	// BEFORE $NofExtra = get_nof_songs_in_set($rID, -1);
	$NofExtra = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, -1);
	if (($nofSongs + $NofExtra) > 17)
	{
		    // If the songs does not fit from Set 1 and Extra's divide them by a page
		    echo "<p style=\"page-break-before: always\">\n<br>";
	}
	else
	{
			// echo "$NofRows + $NofExtra) <= 17<br>";
	}
	echo "<p><table border=0 cellspacing=0>\n<tr></tr><tr>\n";
	if ($NofExtra > 0)
		echo "<hr><font size=6>EXTRA ($NofExtra)</font></tr><tr></tr>";
	for ($i = 1; $i <= $NofExtra; $i++)
	{
	  	echo "<tr>\n\t";
		ubd_rp_printNiceSongRow($debug, $bandID, $rID, -1, $i, 6, $HideIcons, $userID, $lyricsPHP);
	  	echo "</tr>\n";
	}
	echo "</tr></table>\n<p>";
/* BELOW WILL NEVER HAPPEN
	if ($layout == "wholeRepertoir")
	{
 		echo "<p style=\"page-break-before: always\">\n<br>";
		ubd_rp_dprintSetSongs($rID, -1, $NofExtra, $chordLayout, $userID, $bandID); // Extra songs
	} // WHOLE REPERTOIR
========================================= */ 
}


// THIS METHOD IS USED WHEN EDITING SONG LISTS (REPETOIRS)
// It prints the songs in different columns and rows
//
//	Auxiliary methods:
//		
//		print_song_row
//		get_nof_sets_in_repertoir
//		print_set_headers
//		GetMaxNofSongsPerSet
//		print_song_row
//		print_extra_songs($rID);

function ubd_edit_repertoir_songs($debug, $bandID, $rID, $bandMgmtPHP)
{
	//$debug =1;
	if ($debug) up_note("ubd_edit_repertoir_songs($debug, bIDdb::$bandID, rID:$rID, PHP:$bandMgmtPHP): ENTER", 2);  
	// STATUS 	
	// 0 = Inrepad
	// 1 = F�rslag
	// 2 = F�rkastad (Papperskorg)
	// 3 = N�sta rep
	// 4 = Tidigare spelad (lagd p� is)

	// Select songs with specific status - 0 or 3
	// AND  bands.lyrics_status.songStatus='0'
	$query= "SELECT * FROM bands.new_lyrics, bands.lyrics_status WHERE  bands.new_lyrics.bandID='$bandID' AND bands.new_lyrics.bandID=bands.lyrics_status.bandID AND bands.new_lyrics.uID=bands.lyrics_status.songID AND (bands.lyrics_status.songStatus='3' OR bands.lyrics_status.songStatus='0') ORDER BY title";  // (bands.lyrics_status.songStatus=0 OR bands.lyrics_status.songStatus=3)

//	up_error("FIXME: <pre>$query</pre>");
//	up_error("FIXME: Select from multiple Tables (lyrics_status) where lyrics Status = 0 = Inrepad, 3 = N�sta rep, or 1 = F�rslag");

	if ($debug) up_note("ubd_edit_repertoir_songs($rID) - $query", 2);
	$result = mysql_query ($query);

	$nofSets = ubd_rp_get_nof_sets_in_repertoir($debug, $bandID, $rID);
	if ($debug) up_note("ubd_edit_repertoir_songs: Nof sets= $nofSets (rid=$rID)<br>", 2);
	
// 	echo "<table border=1 cellspacing=0 >";
	up_table_header(0);
	up_table_column("<!--set selector-->");
	up_table_column("L�t", 1);
	ubd_rp_print_edit_set_headers($debug, $nofSets, -1);
	echo "</tr>";

	$IthSong = 1;
  	while ($row = mysql_fetch_array ($result)) // GET ALL LYRICS not in Papperskorg
	{	  		
	   if (1) // !IsSongAlreadySelected($row[artist], $row[title], $rID))  // NOT ALLOW MULTIPLE USE
	   {
			ubd_rp_print_song_row($debug, $bandID, $row[songID], $rID, $nofSets, $IthSong, $bandMgmtPHP);
			$IthSong++;
	   }	   
	   else
	   {
		// echo "<li> $row[title] already selected.\n<br>";
	   }
	}
	$NofRows = ubd_rp_GetMaxNofSongsPerSet($debug, $bandID, $rID, $nofSets);
	if ($IthSong < $NofRows)
	{
		// uppdatera med resten av l�tarna
		for ($IthSong = $IthSong; $IthSong <= $NofRows;$IthSong++)
		{
			echo "Printing song:$IthSong (of $NofRows)";
			ubd_rp_print_song_row($debug, $bandID, "", "", $rID, $nofSets, $IthSong, $bandMgmtPHP);
		}
	}
	else
	echo "</table>\n";
	ubd_rp_print_extra_songs2($debug, $bandID, $rID, $bandMgmtPHP);
	echo "<hr>\n";
	$HideIcons = 1;
	if ($debug) up_note("ubd_edit_repertoir_songs($debug, $bandID, $rID): EXIT", 2);  
}

function ubd_get_orderNo($debug, $bandID, $rID, $sID, $songID)
{
	if ($debug) up_note("ubd_get_orderNo($debug, bID:$bandID, rID:$rID, set:$sID): ENTER", 3);  
	$query = "SELECT * FROM bands.repertoir_songs WHERE repertoirID = '$rID' AND setNo='$sID' AND title= '$title'";
 	$result = mysql_query ($query);

	$row = mysql_fetch_array ($result);

	return $row[orderNo];
}

function  ubd_rp_update_repertoir_song($debug, $bandID, $rID, $sID, $currentPosition, $songID, $subaction, $newPosition)
{
	if ($debug) up_note("ubd_rp_update_repertoir_song($debug, bID:$bandID, rID:$rID, set:$sID, currentPosition:$currentPosition, songID:$songID, sAction:$subaction, newPos:$newPosition): ENTER", 2);  

	if ($bandID == ""|| $rID== ""||  $sID == ""|| $currentPosition == "" ||  $newPosition== "" ||  $songID == "")
	{
		up_error("ubd_rp_update_repertoir_song($debug, bID:$bandID, rID:$rID, set:$sID, cPos:$currentPosition, sAction:$subaction, newPos:$newPosition, songID:$songID): Illegal NULL parameter.");
		return;
	}

	// Moving upwards or downwards in the list?
	// - Moving upwards does NOT inflict on succeding order
	// - Moving downwards does NOT inflict on preceding order
//	$currentPosition = ubd_get_orderNo($debug, $bandID, $rID, $sID, $currentPosition);
	
	if($currentPosition == "")
	{
		up_error("ubd_rp_update_repertoir_song($debug, bID:$bandID, rID:$rID, set:$sID, songID:$songID, sAction:$subaction, newPos:$newPosition): currentPosition == NULL!");
		return;
	}

	// Update the order number of succeding songs

 	//echo "Title: $title > new - $newPosition; cur - $currentPosition";
	if ($currentPosition > $newPosition) // Moving upwards in list
	{
 	   // UPDATE THE GIVEN SONG
	   $query2 = "UPDATE bands.repertoir_songs SET orderNo='$newPosition' WHERE orderNo='$currentPosition' AND repertoirID='$rID' AND setNo='$sID' AND songID='$songID'";
	   $result2 = mysql_query ($query2);
		
	   if($debug) up_note("SQL (upwards): $query2");
  
	   if ($result2 != 1)
	   {
	   		up_error("Unable to update the Given song from position '$currentPosition' to '$newPosition'.");
	   		$msg = mysql_error();
	   		if($msg)
	   			up_error("SQL: $msg");
	   }

	   // UPDATE THE OTHERS... (Make a slot)
         
	   for ($i = $currentPosition-1; $i >= $newPosition; $i--)
	   {
			//echo "UPWARD - Updating song $i\n<br>";
			$iPlusOne = $i + 1;
              $iTmp = $i;
			//echo "UPDATE r_songs SET orderNo='$iPlusOne' WHERE orderNo = '$i' AND rID = '$rID' AND setNo = '$sID' ANDtitle<>'$title'\n<br>";
			$query = "UPDATE bands.repertoir_songs SET orderNo='$iPlusOne' WHERE orderNo='$iTmp' AND repertoirID='$rID' AND setNo='$sID' AND songID<>'$songID'";  //  AND songID<>'$songID'

			if($debug) up_note("SQL (upwards): $query");
			$result = mysql_query ($query);
			if ($result != -1)
			{
		   		up_error ( "ubd_rp_update_repertoir_song: Failed to update song rID = '$rID' AND setNo = '$sID' AND orderNo = '$i\n<br>");
		   		$msg = mysql_error();
		   		if($msg)
		   			up_error($msg);
			}
	   }
	
	}
	else	// MOVING DOWNWARDS IN THE LIST
	{
	   // MOVE THE GIVEN SONG
	   $query = "UPDATE bands.repertoir_songs SET orderNo='$newPosition' WHERE orderNo='$currentPosition' AND repertoirID='$rID' AND setNo='$sID' ";
	   $result = mysql_query ($query);
		if($debug) up_note("SQL (downwards): $query");
	
	   // AND UPDATE THE OTHERS...
	   for ($i = $currentPosition+1; $i <= $newPosition; $i++)
	   {
			//echo "DOWNWARD - Updating song $i\n<br>";
			$iMinusOne = $i - 1;
			//echo "UPDATE repertoir_songs SET orderNo='$iMinusOne' WHERE repertoirID = '$rID' AND setNo = '$sID' AND orderNo = '$i'\n<br>";
			$query = "UPDATE bands.repertoir_songs SET orderNo='$iMinusOne' WHERE orderNo='$i' AND repertoirID='$rID' AND setNo='$sID' AND songID<>'$songID'";

			$result = mysql_query ($query);

			if ($result != -1)
			{
		   		up_error( "ubd_rp_update_repertoir_song: Failed to update song rID = '$rID' AND setNo = '$sID' AND orderNo = '$i");
		   		$msg = mysql_error();
		   		if($msg)
		   			up_error($msg);
			}
	   }
	}
	ubd_rp_UpdateLastUpdate($rID);

	if ($debug) up_note("ubd_rp_update_repertoir_song($debug, $bandID, $rID, $sID, $artist, $title, $subaction, $newPosition): EXIT", 2);  

}
// ============================================
//	VIEW - REPERTOIR
// ============================================

function 	ubd_rp_print_table_header($debug, $bandID)
{
	$bgcolor	= "#adcdfe"; // BEFORE ddddee  // 6ed0fc
	$bold 		= 1;
 	$italic 	= 1;
 	$align 		= "left";
 	$size  		= "";
  	$right  	= 1;		// alignRight
	$valign 	= "baseline";
 	$open 		= FALSE;
 	$colspan 	= 1;
 	$underline 	= 1;	
	// ======== HEADER
	/// Title with filter
	up_table_column("<br>Namn<br>", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
//	up_table_column("<br>#Set", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("Lyrics<br>PDF", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("Short<br>PDF", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("<br>Edit", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("<br>Copy", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("<br>Delete", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("<br>Last update", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_new_table_row();
}

function ubd_rp_print_all_repertoirs($debug, $action, $bandID, $bandMgmtPHP, $ShowOld)
{
	if ($debug) up_note("ubd_rp_print_all_repertoirs($debug, $action, $bandID, $bandMgmtPHP): ENTER", 2);

	if ($ShowOld)
		$query 	= "SELECT * FROM bands.repertoir WHERE bandID='$bandID' AND hidden='1' ORDER BY repertoirID DESC";
	else
		$query 	= "SELECT * FROM bands.repertoir WHERE bandID='$bandID' AND hidden='0' ORDER BY repertoirID DESC";
	if ($debug) up_note("sql=$query", 2);
	
 	$result = mysql_query ($query);
	
//	$startPageRid = get_start_page_rID();
	// echo "<ul><font size=5>\n";
	//echo "<table border=0>\n<tr>\n";
	up_table_header();
	ubd_rp_print_table_header($debug, $bandID);
  	while ($row = mysql_fetch_array ($result))
	{		
	   if ($action == "EDIT")
	   {
	     	echo "<td>";
	     	echo " <a href=\"repertoir.php?action=1&rID=$row[repertoirID]\" style=\"text-decoration: none;\">$row[name]</a><br>";
	     	echo "</td></tr><tr>\n";

          }
	   else if ($action == "VIEW")
	   {
// ub_nice_link($URL, $label, $target, $title, $tableColumn, $onMouseOver, $onMouseOut, $fontSize, $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, $fontColor)
	     	ub_nice_link("$bandMgmtPHP?action=view_repertoir&bandID=$bandID&rID=$row[repertoirID]", $row[name], "", "Mer info", 1, $onMouseOver, $onMouseOut,  "", $bold, $italic, $valign, $columnWidth, $colspan, $openColumn, $bgcolor, "#5d8df3");
//			up_table_column("", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
			$tableColumn =1;
	     	if ($row[pdf] != "")
	  		{
	  			$align = "center"; $valign = "center";
	  			$lastOnLineUpdate = $row[lastUpdate] . "_" . $row[lastUpdateTime];
	  			$OLDbgcolor = "#ff0000";
	  			$NEWbgcolor = "#00ff00";
	  			if ($row[longPDF_lastUpdate] < $lastOnLineUpdate)
					ub_pdf("$row[pdf]", 25, "", "Varning! PDF �r �ldre �n senaste uppdatering!", $tableColumn, $valign, $columnWidth, $align, $OLDbgcolor);
				else
					ub_pdf("$row[pdf]", 25, "", "Visa pdf f�r l�tlistan", $tableColumn, $valign, $columnWidth, $align, $NEWbgcolor);
				
			}
			else
			{
				ub_add("$bandMgmtPHP?action=add_repertoir_pdf&bandID=$bandID&rID=$row[repertoirID]", 20, "", "Ladda upp PDF", $tableColumn, "center", "", "center");
			}
	     	if ($row[short_pdf] != "")
	  		{
	  			$align = "center"; $valign = "center";
	  			$lastOnLineUpdate = $row[lastUpdate] . "_" . $row[lastUpdateTime];
	  			$OLDbgcolor = "#ff0000";
	  			$NEWbgcolor = "#00ff00";
	  			if ($row[shortPDF_lastUpdate] < $lastOnLineUpdate)
					ub_pdf("$row[short_pdf]", 25, "", "Varning! PDF �r �ldre �n senaste uppdatering!", $tableColumn, $valign, $columnWidth, $align, $OLDbgcolor);
				else
				ub_pdf("$row[short_pdf]", 25, "", "Visa pdf f�r l�tlistan", $tableColumn, $valign, $columnWidth, $align, $NEWbgcolor);
			}
			else
			{
				ub_add("$bandMgmtPHP?action=add_repertoir_pdf&bandID=$bandID&rID=$row[repertoirID]", 20, "", "Ladda upp PDF", $tableColumn, "center", "", "center");
			}			
//  ub_back($URL, $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor)
	     	ub_edit("$bandMgmtPHP?action=edit_repertoir&bandID=$bandID&rID=$row[repertoirID]", 25, "_self", "Redigera l�tlistan", $tableColumn, "center");
	     	ub_copy("$bandMgmtPHP?action=print_copy_repertoir_form&bandID=$bandID&rID=$row[repertoirID]&layout=$layout", 25, "", "Skapa en kopia av l�tlistan", $tableColumn, "center");
	     	ub_delete("$bandMgmtPHP?action=delete_repertoir&bandID=$bandID&rID=$row[repertoirID]", 25, "_self", "Ta bort l�tlistan", $tableColumn, "center");
// up_table_column($text, $bold, $align, $hsize, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)
			up_table_column("$row[lastUpdate], $row[lastUpdateTime]", $bold, "", "", "center");
	     //	ub_star("$bandMgmtPHP?action=17&bandID=$bandID&rID=$row[repertoirID]" , 25);
	     	if($row[repertoirID] == $startPageRid)
	     		echo "*";
	     	up_new_table_row();
          }
	   
	}
	echo "</tr></table>\n</font></ul>\n";
	if ($debug) up_note("ubd_rp_print_all_repertoirs($debug, $action, $bandID, $bandMgmtPHP): EXIT", 2);

}

function ubd_rp_view($debug, $bandID, $repertoirID, $bandMgmtPHP, $lyricsPHP, $layout, $PREVIOUS_ACTION, $bResponsive) // If rehersalID == "" => View All
{
	// $debug = 1;
	if ($debug) up_note("ubd_rp_view ($debug, $bandID, $repertoirID, $bandMgmtPHP, $lyricsPHP, $layout, <b>pAction</b>:'$PREVIOUS_ACTION'): ENTER");
	$debug = 0;
	
	if ($repertoirID == "")
	{
	    if ($debug) up_note("ubd_rp_view: printing all");
		// PRINT All
		ubd_rp_print_all_repertoirs($debug, "VIEW", $bandID, $bandMgmtPHP);
	}
	else // PRINT SPECIFIED
	{
	    if ($debug) up_note("ubd_rp_view: printing selected");
		ubd_rp_print_nice_repertoir($debug, $bandID, $repertoirID, $layout, $chordLayout, $userID, $HideIcons, $bandMgmtPHP, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
	}
	if ($debug) up_note("ubd_rp_view($debug, $bandID, $repertoirID):  EXIT");
}

function ubd_rp_view_old_repertoirs($debug, $bandID, $repertoirID, $bandMgmtPHP, $lyricsPHP, $layout, $PREVIOUS_ACTION) // If rehersalID == "" => View All
{
	// $debug = 1;
	if ($debug) up_note("ubd_rp_view_old_repertoirs ($debug, $bandID, $repertoirID, $bandMgmtPHP, $lyricsPHP, $layout, <b>pAction</b>:'$PREVIOUS_ACTION'): ENTER");
	$debug = 0;
	
	$OLD = 1;
	ubd_rp_print_all_repertoirs($debug, "VIEW", $bandID, $bandMgmtPHP, $OLD);

	if ($debug) up_note("ubd_rp_view_old_repertoirs($debug, $bandID, $repertoirID):  EXIT");
}

// ============================================
//	REPERTOIR - Aux. functions
// ============================================

function ubd_rp_get_nof_sets_in_repertoir($debug, $bandID, $rID)
{
	if ($bandID == "" || $rID == "")
	{
	 	up_error("ubd_rp_get_nof_sets_in_repertoir: Invalid NULL parameter");
	 	return -1;
	}
	if($debug) up_note("ubd_rp_get_nof_sets_in_repertoir($debug, $bandID, $rID): ENTER");
	$query = "SELECT * FROM bands.repertoir WHERE repertoirID = '$rID'";
 	if($debug) up_note($query, 2);
	$result = mysql_query ($query);

	$i = 0;
  	while ($row = mysql_fetch_array ($result))
	{	  		
	   $i = $row[nofSets];
	}
	if($debug) up_note("ubd_rp_get_nof_sets_in_repertoir($debug, $bandID, $rID): EXIT - returning =$i=");

	return $i;
}

function ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $sID)
{
	// $debug = 1;
	// up_note("ubd_rp_get_nof_songs_in_set($debug, bID:$bandID, rID:$rID, set:$sID): ENTER", 3);
	if($debug) up_note("ubd_rp_get_nof_songs_in_set($debug, bID:$bandID, rID:$rID, set:$sID): ENTER", 3);

	$sql = "SELECT COUNT(bandID) AS NofSongs FROM bands.repertoir_songs WHERE setNo='$sID' AND repertoirID='$rID' AND bandID='$bandID'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array ($result);
   
    $NofSongs = $row[NofSongs];	   	
	if($debug) up_note("ubd_rp_get_nof_songs_in_set($debug, bID:$bandID, rID:$rID, set:$sID): EXIT - returning -$NofSongs-", 3);
	return $NofSongs;
}

function ubd_rp_get_nof_manuscripts_in_set($debug, $bandID, $rID, $sID)
{
	//$debug = 1;
	// up_note("ubd_rp_get_nof_manuscripts_in_set($debug, bID:$bandID, rID:$rID, set:$sID): ENTER", 3);
	if($debug) up_note("ubd_rp_get_nof_manuscripts_in_set(dbug:$debug, bID:$bandID, rID:$rID, set:$sID): ENTER", 3);

	$sql = "SELECT COUNT(repertoirID) AS NofSongs FROM bands.repertoir_talks WHERE setNo='$sID' AND repertoirID='$rID'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array ($result);
   
    $NofSongs = $row[NofSongs];	   	
	if($debug) up_note("ubd_rp_get_nof_manuscripts_in_set($debug, bID:$bandID, rID:$rID, set:$sID): EXIT - returning -$NofSongs-", 3);
	return $NofSongs;
}

function ubd_rp_get_pdf($debug, $bandID, $rID)
{
	if($debug) up_note("ubd_rp_get_pdf($debug, $bandID, $rID): ENTER");
	$query = "SELECT * FROM bands.repertoir WHERE ID='$rID'";
 	$result = mysql_query ($query);

	while ($row = mysql_fetch_array ($result))
	{
		$name = $row[pdf];
	}
	if($debug) up_note("ubd_rp_get_pdf($debug, $bandID, $rID): EXIT - returning -$name-");
 
	return $name;
}

// USED during PRINT NICE REPERTOIR
function ubd_rp_print_set_headers($debug, $nofSets, $userID)
{
		if($debug) up_note("ubd_rp_print_set_headers($debug, nofSets:$nofSets, $userID): ENTER");
		if ($nofSets <= 1)
			return;
       	for($i=1; $i <= $nofSets; $i++)
		{
	   		if ($userID == -1 && $i == 1) // No user - or first column
	   			echo "<td><!--Song Number --></td><th align=left >Set $i</th>";
	   		else if ($userID == -1 && $i > 1)
	   			echo "<td><!--Key --></td><td><!--Song Number --></td><th align=left >Set $i</th>";
	   		else
	   		{	// User comments
				if ($i == 1)
	   				echo "<td><!--Song Number --></td><th align=left >Set $i</th><td><!--Key --></td>"; //<th align=left>Kommentar</th>";
				else 
	   				echo "<td><!--Song Number --></td><th align=left >Set $i</th><td><!--Key --></td>"; // <th align=left>Kommentar</th>";
	   		}
		}
		echo "</tr>";
		if($debug) up_note("ubd_rp_print_set_headers($nofSets, $userID): EXIT");
}

// USED during EDIT
function ubd_rp_print_edit_set_headers($debug, $nofSets, $userID)
{
	// $debug = 1;
		if($debug) up_note("ubd_rp_print_edit_set_headers($debug, nofSets:$nofSets, $userID): ENTER");
       	for($i=1; $i <= $nofSets; $i++)
		{
	   		if ($userID == -1 && $i == 1) // No user - or first column
	   			echo "<td><!--Song Number --></td><th align=left >Set $i</th>";
	   		else if ($userID == -1 && $i > 1)
	   			echo "<td><!--Song Number --></td><th align=left >Set $i</th>";
	   		else
	   		{	// User comments
				if ($i == 1)
	   				echo "<td><!--Song Number --></td><th align=left >Set $i</th>"; //<th align=left>Kommentar</th>";
				else 
	   				echo "<td><!--Song Number --></td><th align=left >Set $i</th>"; // <th align=left>Kommentar</th>";
	   		}
		}
		echo "</tr>";
		if($debug) up_note("ubd_rp_print_edit_set_headers($nofSets, $userID): EXIT");
}

function ubd_rp_GetMaxNofSongsPerSet($debug, $bandID, $rID, $nofSets)
{
	//$debug = 1;
	if($debug) up_note(" ubd_rp_GetMaxNofSongsPerSet($debug, $bandID, $rID, $nofSets): ENTER " , 2);
	$iMax = 0;
	for($i=1; $i<=$nofSets;$i++)
	{
	   $iSongsInSet =  ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $i);
	   $iMScriptsInSet =  ubd_rp_get_nof_manuscripts_in_set($debug, $bandID, $rID, $i);
	   $Total =  $iSongsInSet + $iMScriptsInSet;
	   if($iMax < $Total)
			$iMax = $Total;
	}
	if($debug) up_note(" ubd_rp_GetMaxNofSongsPerSet($bandID, $rID, $nofSets): EXIT =$iMax= " , 2);
	return $iMax;
}
/*
*/
//	======================== ubd_rp_printNiceSongRow
// Auxilliary methods
//		getNiceKey
// 		GetSingerByArtistTitle
//		get_short_title
//		GetSingerStatus
//		GetMaxNofSongsPerSet
//		IsSingleStart
//		GetSingleStartInstrument

function ubd_rp_printNiceSongRow($debug, $bandID, $rID, $sID, $iThSong, $fontSize, $NoIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive)
{
//	$debug = 1;
	if ($debug) up_note("ubd_rp_printNiceSongRow($debug, bID:$bandID,  rID:$rID, set:$sID, song:$iThSong, fontSize= $fontSize, $NoIcons, $userID) : ENTER", 2);
	$debug = 0;
	
	$query = "SELECT * FROM bands.repertoir_songs WHERE repertoirID='$rID' AND setNo='$sID' AND orderNo ='$iThSong'";
 	$result = mysql_query ($query);

	$row = mysql_fetch_array ($result);
// uly_get_lyrics_data($debug, $dbase, $table, $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize)
	
	if ($row[songID] != "")
	{
		uly_get_lyrics_data($debug, "bands", "new_lyrics", $row[songID], $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize);
	}
	else
	{
		//up_table_column("");
//		up_error("Song not found.");
	}
	echo "\n\t";
	if ($title != "")
	{
		if ($debug) up_note("TITLE $row[title] ");
	//	$title			= $title;
	//	$artist			= $artist;
		$tempArtist 	= str_replace(" ", "+", $artist); // Replace whitespaces with "+"
		$tempTitle 		= str_replace(" ", "+", $title); // Replace whitespaces with "+"
		$NiceKey		= ubdc_get_key($debug, $bandID, $title, $artist);
		if ($sID > 1)
			$title 			= ubdc_get_short_title($debug, $title, 18);
		$Singer 		=  ubdc_get_singer($bandID, $row[songID]); // local - returns the initial letter
		$fontSize = 5;
		// echo "$Singer";
		// OM ETT INSTRUMENT STARTAR L�TEN SKA DEN VARA "FETSTIL"
		
		// AFTER
		// $maxSongPreviousSet = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $sID-1);
		// BEFORE
		$maxSongPreviousSet = ubd_rp_GetMaxNofSongsPerSet($debug, $rID, $sID-1);
		if ($sID > 1 && $iThSong > $maxSongPreviousSet && $uID != -1 && $userID != -1 )
		{
			//echo "<td></td>";
		}
		if ($debug)  echo "<li>$iThSong. fontsize= $fontSize";
		// PRINT Ith SONG COLUMN
		echo "<td align=right bgcolor=\"#ffffff\"><font size=$fontSize>$iThSong. </td>";
		// PRINT TITLE COLUMN
		$fontColor = "#0000aa";
		if($sID != -1) // Inte extra nummer
		{
			ub_nice_link("$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title, "", $title, 1, "", "", $fontSize, 1, 0, "", "",  "", 1, $bgColor, $fontColor);
		}
		else // Extra nummer
		{
			ub_nice_link("$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title, "", $title, 1, "", "", $fontSize, 1, 0, "", "",  "", 1, $bgColor, $fontColor);
		}
		// PRINT KEY COLUMN
		if($fontSize >= 6) // ie. One set per page
		{
			 echo "</td><td bgcolor=\"#ffffff\"><font size=4  color=$bgcolor>($Singer)</font></td>";
			 echo "<td align=center><font size=4>($originalKey)</font></td>";
		}
		else
		{
			echo " <font size=$fontSize>($Singer)</font></td>";
			echo "<td align=center><font size=$fontSize>($originalKey)</font></td>";
		}	
	}
	else  // No Title
	{
		echo "<td></td><td></td><td></td>"; // <td></td>";
	}
	echo "\n";
/**/
	if ($debug) up_note("ubd_rp_printNiceSongRow($debug, $bandID,  $rID, $sID, $iThSong, fontSize= $fontSize, $NoIcons, $userID) : EXIT", 2);

}

function ubd_rpr_printNiceSongRow($debug, $bandID, $rID, $sID, $iThSong, $fontSize, $NoIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive)
{
//	$debug = 1;
	if ($debug) up_note("ubd_rpr_printNiceSongRow($debug, bID:$bandID,  rID:$rID, set:$sID, song:$iThSong, fontSize= $fontSize, $NoIcons, $userID) : ENTER", 2);
	$debug = 0;
	
	$query = "SELECT * FROM bands.repertoir_songs WHERE repertoirID='$rID' AND setNo='$sID' AND orderNo ='$iThSong'";
 	$result = mysql_query ($query);

	$row = mysql_fetch_array ($result);
// uly_get_lyrics_data($debug, $dbase, $table, $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize)
	
	if ($row[songID] != "")
	{
		uly_get_lyrics_data($debug, "bands", "new_lyrics", $row[songID], $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize);
	}
	echo "\n\t";
	if ($title != "")
	{
		if ($debug) up_note("TITLE $row[title] ");
		$tempArtist 	= str_replace(" ", "+", $artist); // Replace whitespaces with "+"
		$tempTitle 		= str_replace(" ", "+", $title); // Replace whitespaces with "+"
		$NiceKey		= ubdc_get_key($debug, $bandID, $title, $artist);
		if ($sID > 1)
			$title 			= ubdc_get_short_title($debug, $title, 18);
		$Singer 		=  ubdc_get_singer($bandID, $row[songID]); // local - returns the initial letter
		$fontSize = 5;
		// echo "$Singer";
		// OM ETT INSTRUMENT STARTAR L�TEN SKA DEN VARA "FETSTIL"
		
		// AFTER
		// $maxSongPreviousSet = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $sID-1);
		// BEFORE
		$maxSongPreviousSet = ubd_rp_GetMaxNofSongsPerSet($debug, $rID, $sID-1);
		if ($sID > 1 && $iThSong > $maxSongPreviousSet && $uID != -1 && $userID != -1 )
		{
			//echo "<td></td>";
		}
		if ($debug)  echo "<li>$iThSong. fontsize= $fontSize";
		// PRINT Ith SONG COLUMN
		ucss_column_start($debug, "repertoirIthSongColumn");
		echo "$iThSong.";
		//	ub_nice_link("$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title, "", $title, 1, "", "", "", 1, 0, "", "",  "", 1, $bgColor, $fontColor);
		ucss_column_end();

	//	echo "<td align=right bgcolor=\"#ffffff\"><font size=$fontSize>$iThSong. </td>";
		// PRINT TITLE COLUMN
		$fontColor = "#0000aa";
		if($sID != -1) // Inte extra nummer
		{
			if ($bResponsive)
			{
				ucss_column_start($debug, "repertoirSongTitleColumn");
//				echo "<a href=$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION>";
			//	echo "$title";
//				echo "</a>";
				ub_nice_link("$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title, "", $title, 0, "", "", "", 1, 0, "", "",  "", 1, $bgColor, $fontColor);
				ucss_column_end();
			}
			else // non-responsive
				ub_nice_link("$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title, "", $title, 1, "", "", $fontSize, 1, 0, "", "",  "", 1, $bgColor, $fontColor);
		}
		else // Extra nummer
		{
			ub_nice_link("$lyricsPHP?action=view_lyric&bandID=$bandID&songID=$row[songID]&key=*&bChords=1&layout=ChordsIntegrated&printerFriendly=0&status=0&PREVIOUS_ACTION=$PREVIOUS_ACTION", $title, "", $title, 1, "", "", $fontSize, 1, 0, "", "",  "", 1, $bgColor, $fontColor);
		}
		// PRINT KEY COLUMN
			ucss_column_start($debug, "repertoirKeyColumn");
			echo "($originalKey)";
			ucss_column_end();
			ucss_column_start($debug, "repertoirSingerColumn");
			echo "($Singer)";
			ucss_column_end();
			/*
		if($fontSize >= 6) // ie. One set per page
		{
				
			ucss_column_start($debug, "repertoirSingerColumn");
			echo "($Singer)";
			ucss_column_end();
			// echo "</td><td bgcolor=\"#ffffff\"><font size=4  color=$bgcolor>($Singer)</font></td>";
			// echo "<td align=center><font size=4>($originalKey)</font></td>";
		}
		else
		{
			ucss_column_start($debug, "repertoirKeyColumn");
			echo "($originalKey)";
			ucss_column_end();
//			echo " <font size=$fontSize>($Singer)</font></td>";
//			echo "<td align=center><font size=$fontSize>($originalKey)</font></td>";
		}	
		*/
	}
	else  // No Title
	{
		// echo "<td></td><td></td><td></td>"; // <td></td>";
	}
	echo "\n";
/**/
	if ($debug) up_note("ubd_rpr_printNiceSongRow($debug, $bandID,  $rID, $sID, $iThSong, fontSize= $fontSize, $NoIcons, $userID) : EXIT", 2);

}
// ============================================
//	MANUSCRIPT / Check / ADD / DELETE
// ============================================
function ubd_rp_getUIDmanuscript()
{
    $query = "SELECT MAX(uID) AS MaxUID FROM bands.repertoir_talks";
    $result = mysql_query ($query);	

    $row = mysql_fetch_array ($result);
   
    //echo "getUID() = $row[MaxUID]<br>\n";
    $maxUID = $row[MaxUID];
    if ($maxUID == "")
		return 1;	// START AT 1
    else
    {
		$maxUID = $maxUID + 1;
       return $maxUID;
    }
}

function ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $iSet, $IthSong)
{
	if ($debug) up_note ("ubd_rp_DoesManuscriptExist($debug, bID:$bandID, rID:$rID, set:$iSet, iThSong:$IthSong)", 2);
	$query = "SELECT * FROM bands.repertoir_talks WHERE afterIthSong= '$IthSong' AND setNo = '$iSet' AND repertoirID ='$rID'";
 	$result = mysql_query ($query);

	if ($row = mysql_fetch_array ($result))
	{
		if ($debug) up_note ("ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $iSet, $IthSong) : TRUE", 2);
		return $row[uID];
	}
	if ($debug) up_note ("ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $iSet, $IthSong) : FALSE", 2);
	return FALSE;
}

function ubd_rp_add_manuscript($debug, $bandID, $iSet, $iThSong, $rID)
{
	if ($debug) up_note ("ubd_rp_add_manuscript($debug, bID:$bandID, set:$iSet, iThSong:$iThSong, rID:$rID )", 1);
	if (ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $iSet, $iThSong))
	{
		up_info("Manuscript already exist.");
		return;
	}
	else
	{
		if ($debug) up_info("Adding Manuscript.");
	}
	// echo "";
	$uID =  ubd_rp_getUIDmanuscript();
    $sql = "INSERT INTO bands.repertoir_talks (uID, repertoirID, setNo, afterIthSong) VALUES ('$uID','$rID', '$iSet', '$iThSong')";
    $result = mysql_query ($sql) or die ("Error inserting " . mysql_error());
 
    if ($result != 1)
    {
	 	echo "<li> add_manuscript: Failed to insert the manuscript in the repertoir $rID set:$iSet.";
    }	
	ubd_rp_UpdateLastUpdate($rID);
}

function ubd_rp_delete_manuscript($debug, $bandID, $uID)
{
    $query = "DELETE FROM bands.repertoir_talks WHERE  uID = '$uID'";
    $result = mysql_query ($query);

    if ($result != -1)
    {
		up_error( "delete_manuscript: Failed to delete manuscript.");
		$msg = mysql_error();
		up_error( $msg );
	}
	ubd_rp_UpdateLastUpdate($rID);
}

// ============================================
//	PRINT SET SONGS - NICE REPERTOIR
// ============================================

// Auxilliary methods
//	OK	ubd_rp_get_nof_sets_in_repertoir OK
//	OK	ubd_rp_get_repertoir_name
//	OK	ubd_rp_get_pdf
//	OK	ubd_rp_print_set_headers
//	OK	ubd_rp_GetMaxNofSongsPerSet   uses ubd_rp_get_nof_songs_in_set
//		ubd_rp_printNiceSongRow
//		DoesManuscriptExist
//	OK	ubd_rp_get_nof_songs_in_set
//		printSoloArtists

function ubd_rp_print_nice_repertoir($debug, $bandID, $rID, $layout, $chordLayout, $userID, $HideIcons, $bandMgmtPHP, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive)
{
	// $debug = 1;
	if ($debug) up_info("ubd_rp_print_nice_repertoir($debug, id:$bandID, rid:$rID, $layout, $chordLayout, $userID, hideIcon:$HideIcons, PHP:$bandMgmtPHP, PREV_ACTION:'$PREVIOUS_ACTION')): ENTER", 2);

     $debug = 0;
	$nofSets 	= ubd_rp_get_nof_sets_in_repertoir($debug, $bandID, $rID);
	$name 		= ubd_rp_get_repertoir_name($debug, $bandID, $rID);
    $pdf 		= ubd_rp_get_pdf($debug, $bandID, $rID);
    ubd_rp_get_repertoir_data($debug, $rID, &$name, &$nofSets, &$pdf, &$lastUpdateDate,  &$lastUpdateTime);
   //  $debug = 1;
	echo "<font size=1>\n";
	
	if ($PREVIOUS_ACTION == "")
		$PREVIOUS_ACTION = "$bandMgmtPHP" . "QM" . "actionEQUALSview_repertoirANDbandIDEQUALS" . $bandID . "ANDrIDEQUALS" . $rID;

	if ($debug) echo "<li> PREV_ACTION= $PREVIOUS_ACTION";		

   	if ($layout == "" || $layout == "allSetsOnOnePage")
   	{	// ALL sets on one page
		//up_info("allSets");
		if ($layout == "")
			$layout = "\"\"";

		if (!$bResponsive)
		{
			echo "<table border=0 cellspacing=0>\n<tr>\n";
			ubd_rp_print_set_headers($debug, $nofSets, $userID);
			echo "</tr>\n";	
		}
		else
		{
			ucss_section_start($debug, "repertoirSongRowSection");
		}
		// ============================================================================
		// 1. PERFORM PREPROCESSING
		// Go through each set and create an array for each row.
		// A row can consist of a song (songID) or manuscript (-1)
		// After preprocessing the max nof. rows is determined and the array (or matrix) is 
		// parsed through.
		
		for($iThSet=1; $iThSet<=$nofSets; $iThSet++)
	    {	
	    	$nofManuscripts = 0;
	    	$nofSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $iThSet);

			for($iThSong=1; $iThSong<=$nofSongs; $iThSong++)
			{
	    		$iThRow = $iThSong + $nofManuscripts;
	    		if (ubd_rp_DoesManuscriptExist($debug, $bandID,$rID, $iThSet, $iThSong))
	    		{
	    			$SetSong[$iThSet][$iThRow] = $iThSong;
	    			$SetSong[$iThSet][$iThRow+1] = -1;
	  				$nofManuscripts++;	
	    		}
	    		else
	    		{
	    			$SetSong[$iThSet][$iThRow] = $iThSong;	    		
	    		}
			}
		}

		// ============================================================================
		// 2. PERFORM PRINT OUTS
		// => TABLE ROWS
		$nofSongs = ubd_rp_GetMaxNofSongsPerSet($debug, $bandID, $rID, $nofSets);
	    //$nofSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $iThSet);
		// $debug =1;
		if($debug) up_note("NofRows = $NofRows<br>\n");
		if($debug) up_note("nofSongs = $nofSongs<br>\n");

		for($iThRow=1; $iThRow<=$nofSongs+$nofManuscripts; $iThRow++)
		{
			// echo "<li> Set : $iThSet";
	 //   	$nofSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $iThSet);
	 //   	$nofManuscripts = ubd_rp_get_nof_manuscripts_in_set($debug, $bandID, $rID, $iThSet);
			if (!$bResponsive) 
				echo "<tr>";
			for($iThSet=1; $iThSet<=$nofSets; $iThSet++)
	    	{	
				if (!$bResponsive)
				{
					if ($SetSong[$iThSet][$iThRow] != -1)
						ubd_rp_printNiceSongRow($debug, $bandID, $rID, $iThSet, $SetSong[$iThSet][$iThRow], 5, $HideIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
					else 
						echo "<td colspan=3><hr></td>";
				}
				else
				{
					if ($SetSong[$iThSet][$iThRow] != -1)
						ubd_rpr_printNiceSongRow($debug, $bandID, $rID, $iThSet, $SetSong[$iThSet][$iThRow], 5, $HideIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
					else 
					{
						ucss_section_end($debug, "repertoirSongRowSection");
						ucss_section_start($debug, "repertoirSongRowSection");
						echo "<hr>";
						ucss_section_end($debug, "repertoirSongRowSection");
						ucss_section_start($debug, "repertoirSongRowSection");
						// echo "<td colspan=3><hr></td>";
					}
						
				
				}
    			//echo "<li> $iThRow. $SetSong[$iThSet][$iThRow]";	    			
			}	
			if (!$bResponsive)
			{			
				echo "</tr>";
			}
			else
			{
				ucss_section_end($debug, "repertoirSongRowSection");
				ucss_section_start($debug, "repertoirSongRowSection");
			//ucss_section_start($debug, "section3");
			}
		}	
		if (!$bResponsive)
		{
			echo "</table><p>\n";
		}
		else
		{
			ucss_section_end($debug, "repertoirSongRowSection");
		}
		// EXTRA SONGS
		if (!$bResponsive)
		{
			echo "<table border=0 cellspacing=0>\n<tr>\n";
			$NofExtra = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, -1);
			if ($NofExtra > 0)
				echo "<hr><font size=6>EXTRA ($NofExtra)</font><p>";		
		}
		else // EXTRA SONGS - RESPONSIVE
		{		
			$NofExtra = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, -1);
			echo "<hr><font size=6>EXTRA ($NofExtra)</font><p>";		
		//	ucss_section_start($debug, "repertoirSongRowSection");
		}

		for ($i = 1; $i <= $NofExtra; $i++)
		{
			if (!$bResponsive)
			{
	  			echo "<tr>\n\t";
	  			ubd_rp_printNiceSongRow($debug, $bandID, $rID, -1, $i, $fontSize, $NoIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
	  			//printNiceSongRow($rID, -1, $i, 5, $HideIcons, $userID);
	  			echo "</tr>\n";
	  		}
	  		else
	  		{
				ucss_section_start($debug, "repertoirSongRowSection");
	  			ubd_rpr_printNiceSongRow($debug, $bandID, $rID, -1, $i, $fontSize, $NoIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
				ucss_section_end($debug, "repertoirSongRowSection");
	  		}
		}
		if (!$bResponsive)
		{
			echo "</tr></table>\n<p>";
		}
		else
		{
			ucss_section_end($debug, "repertoirSongRowSection");		
		}
		return;

   }
   else // if($layout == "oneSetPerPage" || $layout == "wholeRepertoir")	// ONE set per page
   {
   	
		//$debug = 1;
		if($debug) up_info("One set per page: $nofSets", 2);
	     $debug = 0;

		if ($nofSets > 3)
		{
			//echo "ERROR More than 3 sets < '$nofSets'";
			//$nofSets = 2;
		}
 		$lastPrintedSet = 0;
 		$nofExtraSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, -1);
		for ($i = 1; $i <= $nofSets; $i++)
		{
			//  echo "<hr><li> <font size=8>PRINTING SET $i";
			if ($i != 1)
		   		up_page_break();
			if ($i == $lastPrintedSet)
			{
				// echo  "ERROR: Infinite printout";
				break;
			}
			if ($nofSets > 1)
			echo "<font size=7>Set $i</font>\n<p><hr>"; //  - $name
			echo "<table>";
			$nofSongs = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $i);	
			echo "</th></tr>\n";
			for($j = 1; $j <= $nofSongs; $j++)
			{
				echo "<tr>\n\t";
				ubd_rp_printNiceSongRow($debug, $bandID, $rID, $i, $j, 6, $HideIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
	  			echo "</tr>\n";

	    		if (ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $i, $j))
	    		{
					// PRINT BREAK
					echo "<tr><th colspan=6><hr size=3></th></tr>\n";
	    		}
			}
			if ($i == 1)
			{
				$NofExtra = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, -1);
				if ($NofExtra > 0)
					ubd_rp_print_extra_songs2($debug, $bandID, $rID, $bandMgmtPHP);
			}
			echo "</table>";
			$lastPrintedSet = $i;
		
			if($i <= $nofSets)
			{
				// up_info(" $i <= $nofSets");
		  		// echo "<p style=\"page-break-before: always\">\n<br>";
		  		up_page_break();
		  		if ($layout == "wholeRepertoir")
		     		ubd_rp_dprintSetSongs($rID, $i, $nofSongs, $chordLayout, $userID, $bandID);
			}

		} // FOR
		// PRINT EXTRA SONGS
		$i = -1; // I.e. Extra Songs
		if ($nofExtraSongs > 0)
		{
			up_page_break();
			echo "<hr><font size=7>EXTRA ($nofExtraSongs)</font><hr>";
			up_table_header();
			for($j = 1; $j <= $nofExtraSongs; $j++)
			{
				echo "<tr>\n\t";
				ubd_rp_printNiceSongRow($debug, $bandID, $rID, $i, $j, 6, $HideIcons, $userID, $lyricsPHP, $PREVIOUS_ACTION, $bResponsive);
	  			echo "</tr>\n";

	    		if (ubd_rp_DoesManuscriptExist($debug, $bandID, $rID, $i, $j))
	    		{
					// PRINT BREAK
					echo "<tr><th colspan=6><hr size=3></th></tr>\n";
	    		}
			}
			up_table_footer();
			up_page_break();
		  	if ($layout == "wholeRepertoir")
		     	ubd_rp_dprintSetSongs($rID, $i, $nofExtraSongs, $chordLayout, $userID, $bandID);
		}

		
   } // ONE SET PER PAGE / WHOLE REPERTOIR

	if ($debug) up_note("ubd_rp_print_nice_repertoir($bandID, $rID, $layout, $chordLayout, $userID, $HideIcons, $bandMgmtPHP)): EXIT", 2);
}
// ============================================
//	PRINT SET SONGS - REPERTOIR
// ============================================

function ubd_rp_dprintSetSongs($rID, $iThSet, $nofSongs, $chordLayout, $userID, $bandID)
{
	// $debug = 1;
	if($debug) up_note( "ubd_rp_dprintSetSongs($rID, $iThSet, $nofSongs, $chordLayout, $userID): ENTER");
	//up_info("PRINTING SET : $iThSet / #SONGS:$nofSongs");

	for($j = 1; $j <= $nofSongs; $j++)
	{
		ubd_rp_printSetSongLyrics($rID, $iThSet, $j, $chordLayout, $userID, $bandID);
		if ($j !=  $nofSongs)
		   echo "<p style=\"page-break-before: always\">\n<br>";
	}
	if($debug) up_note( "ubd_rp_dprintSetSongs($rID, $iThSet, $nofSongs, $chordLayout, $userID): EXIT");
}

function ubd_rp_printSetSongLyrics($rID, $iThSet, $iThSong, $chordLayout, $userID, $bandID)
{
	//$debug = 1;
	if($debug) up_note( "ubd_rp_printSetSongLyrics(repertoir:$rID, set:$iThSet, song:$iThSong, $chordLayout, $userID): ENTER");
	$query = "SELECT * FROM bands.repertoir_songs WHERE repertoirID = '$rID' AND setNo = '$iThSet' AND orderNo ='$iThSong'";
 	$result = mysql_query ($query);

	$row = mysql_fetch_array ($result);

	 ubd_rp_printLyric123($row[songID], 1, $chordLayout, $userID, $bandID); //$layout);
//	if($debug) up_note( "ubd_rp_printSetSongLyrics($rID, $iThSet, $nofSongs, $chordLayout, $userID): EXIT");
}

function  ubd_rp_printLyric123($songID, $layout, $chordLayout, $userID, $bandID)
{
	//$debug = 1;
	if($debug) up_note( "printLyric123(songID:$songID, layout:$layout, chord:$chordLayout, user:$userID, bandID:$bandID)");
//$result = mysql_query ("SELECT * FROM bands.new_lyrics WHERE uID='$songID'");
  $temp1;
  $temp2;
//uly_get_lyrics_data($debug, $dbase, $table, $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize, $NO_CONVERTION)
	$dbase = "bands";
	$table = "new_lyrics";
	uly_get_lyrics_data($debug, $dbase, $table, $songID, $bandID, &$title , &$artist, &$originalKey, &$lyrics, &$BPM, &$midi, &$mp3, &$playback, &$spotify, &$category, &$dateAdded, &$videoURL, &$language, &$fontSize, $NO_CONVERTION);


 // $row = mysql_fetch_array ($result);
  echo "<center><b><font size=5>$title - $artist ($originalKey)</font></b><br>";
  if ($userID != -1)
  {
     echo "<font size=4>"; 
     // print_user_comments($songID, $userID);
 
  }
     echo "</center><br>";
     echo "<font size=5>\n";
	//	echo "calling: get_lyrics_chords_separated";

	//echo "Layout= $layout<br>";
	if($chordLayout == "ChordsSeparated")
	{
		// Extract the chords
		uly_get_lyrics_chords_separated($lyrics, &$Chords, &$CleanLyrics);
		// Do HTML-transition of the clean lyrics
		//echo $CleanLyrics;
		$htmlLyrics = str_replace("\n", "<BR>", $CleanLyrics);
		echo $htmlLyrics; 	// PRINT THE CLEAN LYRICS
		echo "<P><hr>";	// SEPARATE WITH A BAR
		// Do HTML-transition of the chords
		// Replace chordsPro format '[]' with <sup></sup>-tags.
		$temp2 = str_replace("[", "<SUP>", $Chords);
		// ...and print it in the web-page.
		$htmlChords = str_replace("]", "</SUP>", $temp2);
		echo $htmlChords;
		//PrintChordImages($Chords);	
	}
	else if($chordLayout == "ChordsSeparatedOnSameLine")
	{
		// Extract the chords
		print_lyrics_chords_separated_on_same_line($lyrics, &$Chords, &$CleanLyrics);
		get_lyrics_chords_separated($lyrics, &$Chords, &$CleanLyrics);
		//PrintChordImages($Chords);	
	}
	else if($chordLayout == "NoChords")
	{
		// Extract the chords
		uly_get_lyrics_chords_separated($lyrics, &$Chords, &$CleanLyrics);
		$temp1 = str_replace("\n", "<BR>", $CleanLyrics);
			echo "$temp1";
	}
	else // CHORDS interleaved with LYRICS
	{
		//echo "<li> calling: get_lyrics_chords_separated";
		uly_get_lyrics_chords_separated($lyrics, &$Chords, &$CleanLyrics);
		//echo "<li> calling: get_lyrics_chords_separated...DONE!";
		// Replace new lines with <br>-tags.
		$temp1 = str_replace("\n", "<BR>", $lyrics);
		// Replace chordsPro format '[]' with <sup></sup>-tags.
		$temp2 = str_replace("[", "<SUP>", $temp1);
		if ($fontSize) echo "<font size=$fontSize>";
		echo str_replace("]", "</SUP>", $temp2);
		//PrintChordImages($Chords);
	}
}

// ============================================
//	ADD - REPERTOIR
// ============================================

function ubd_rp_does_exist($debug, $bandID, $name, $nofSets )
{
	if($debug) up_note("ubd_rp_does_exist($debug, $bandID, $name, $nofSets): ENTER");
	
	$sql = "SELECT * FROM bands.repertoir WHERE bandID='$bandID' AND name='$name'";
	
    $result = mysql_query ($sql);	
      
  	while ($row = mysql_fetch_array ($result))
	{	
		return TRUE;
	}
	if($debug) up_note("ubd_rp_does_exist($debug, $bandID, $name, $nofSets): EXIT - (Not found)");
	return FALSE;
}

function ubd_rp_add($debug, $bandID, $name, $nofSets, $bandMgmtPHP  )
{
	if ($debug) up_note("ubd_rp_add: ENTER");

	if (ubd_rp_does_exist($debug, $bandID, $name, $nofSets ))
	{
		up_error("ubd_rp_add(): the repertoir '$name' already exist.");
		return -1;
	}
	if($bandID == "" ||  $name== "" || $nofSets == "")
	{
		if ($debug) 
			up_error("ubd_rp_add($debug, bID:$bandID, name:$name, #sets:$nofSets): Illegal NULL parameters.");
		$errorIndication = 1;
		ubd_rp_print_form($debug, $bandID, $edit, $rehersalID, $name,  $nofSets, $errorIndication, $bandMgmtPHP);

		return -1;
	}
	if ($bandID)
	{
		$variables = "bandID"; 
		$parameters = "'$bandID'"; 
	}
	if ($name)
	{
		$variables = $variables . ", name"; 
		$parameters = $parameters . ", '$name'"; 
	}
	if ($nofSets)
	{
		$variables = $variables . ", nofSets"; 
		$parameters = $parameters . ", '$nofSets'"; 
	}
	if ($pdf)
	{
		$variables = $variables . ", pdf"; 
		$parameters = $parameters . ", '$pdf'"; 
	}	
	if ($hidden)
	{
		$variables = $variables . ", hidden"; 
		$parameters = $parameters . ", '$hidden'"; 
	}	
	
	if ($debug) up_info("Getting Unique id...");
	$uID = ubdc_get_unique_id($debug, "bands", "repertoir", "repertoirID"); // "repertoirID");
	if ($debug) up_info("Getting Unique id...'$uID'.");
	if ($uID <= 0)
	{
		up_error("ubd_rp_add: Illegal unique identifier.");
		return -1;
	}
	if ($uID)
	{
		$variables = $variables . ", repertoirID"; 
		$parameters = $parameters . ", '$uID'"; 	
	}
	// Last update date
	$today = date("Y-m-d");
	$variables = $variables . ", lastUpdate"; 
	$parameters = $parameters . ", '$today'"; 	
	// Last update time
	$currentTime = date("H:i:s");
 	$variables = $variables . ", lastUpdateTime"; 
	$parameters = $parameters . ", '$currentTime'"; 	
   $sql = "INSERT INTO bands.repertoir ( $variables ) VALUES ( $parameters )";
    
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
	// up_info("ubd_rp_add: EXIT - returning: rID:$uID");
	return $uID;		
	if ($debug) up_note("ubd_rp_add: EXIT");
}

// ============================================
//	EDIT - REPERTOIR
// ============================================

function ubd_rp_print_edit_title_form($debug, $bandID, $rID, $targetPHP )
{
	ubd_rp_get_repertoir_data($debug, $rID, &$name, &$nofSets, &$long_pdf, &$lastUpdateDate,  &$lastUpdateTime, &$short_pdf);
	up_form_header($targetPHP);
	up_php_param("action", "update_repertoir", "hidden");
	up_php_param("bandID", "$bandID", "hidden");
	up_php_param("repertoirID", "$rID", "hidden");
	up_table_header();
	up_table_column("Repertoir name:", 1, "right", $hsize, "center", $open, $colspan, $underline, $bgcolor, $width, $fontsize);
	$tableColumn = 1;
	up_php_param("name", $name, "", $tableColumn);
	
	ub_save($URL, $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
	up_table_footer();
	up_form_footer();
} 

// ============================================
//	UPDATE - REPERTOIR
// ============================================

function ubd_rp_update( $debug, $bandID, $rID, $name )	
{
	if ($debug) up_info("ubd_rp_update( $debug, $bandID, $rID, $name )	ENTER");
	if ($name == "")
	{
		up_error("ubd_rp_update: Invalid NULL parameters.");
		return;
	}
	$parameters = "repertoirID='$rID'";

	if ($name)
	{
		$parameters = $parameters . ", name='$name'";
	}
    $sql = "UPDATE bands.repertoir SET $parameters WHERE repertoirID='$rID'";
	$result = mysql_query ($sql);
   	if($result != 1)
   	{
		up_error( "ubd_rp_update: Failed to update the database.");
		$msg = mysql_error();
		up_error( "mysql_error: $msg");
		up_error( "sql:  $sql");
	}
	if ($debug) up_info("ubd_rp_update: EXIT");
}

// ============================================
//	COPY - REPERTOIR
// ============================================

function ubd_rp_print_copy_form($debug, $bandID, $rID, $targetPHP, $errorIndication)
{
	up_form_header("$targetPHP");
	up_php_param("action", "copy_repertoir", "hidden");
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("rID", $rID, "hidden");
	up_table_header();
	up_table_column("Namn:", 1);
	// ($param, $value, $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked, $tableBgColor)

	up_php_param("repertoir_name", $value, $type, 1, 50, $errorIndication);
	up_new_table_row();
	echo "<td></td><td>";
	ub_save();
	up_form_footer();
	ub_cancel("$targetPHP?action=view_repertoirs&bandID=$bandID");
	echo "</td>";
	up_table_footer();

}

function ubd_rp_copy_repertoir($debug, $bandID, $rID, $targetPHP, $name)
{
	if ($debug) up_info("ubd_rp_copy_repertoir($debug, $bandID, $rID, $targetPHP, $name): INSIDE");
	if ($name == "")
	{
		up_error("Invalid repertoir name.");
		ubd_rp_print_copy_form($debug, $bandID, $rID, $targetPHP, 1);
		return;
	}
	// Make a copy of the repertoir. (bands.repertoir)
	// Get the reportoir data	
	$nofSets = ubd_rp_get_nof_sets_in_repertoir($debug, $bandID, $rID);
	// ...and insert
	$NewrID = ubd_rp_add($debug, $bandID, $name, $nofSets, $targetPHP );
	
	if ($NewrID == "")
	{
		up_error("Illegal rID when copying.");
		return -1;
	}
	
	// For all songs in the repertoir .. (bands.repertoir_songs) and bands.repertoir_talks
	for ($iThSet = 1; $iThSet <= $nofSets; $iThSet++)
	{
		$nofSongsInSet = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, $iThSet);
		for ($iThSong = 1; $iThSong <=$nofSongsInSet; $iThSong++)
		{ 
			$songID = ubd_rp_get_song_id ($debug, $rID, $iThSet, $iThSong);
			if ($songID == -1)
			{
				up_error("$iThSong'th Song in set $iThSet not found");
			}
			else
			{
				if ($debug) up_info ("Set:$iThSet song:$iThSong: sID:$songID - adding");
				ubd_rp_add_repertoir_song($debug, $bandID, $NewrID, $iThSet, $songID, $comment);
			}
		}
	}
	// Extra numbers
	
	$iThSet = -1;
	$nofSongsInSet = ubd_rp_get_nof_songs_in_set($debug, $bandID, $rID, -1);
	for ($iThSong = 1; $iThSong <=$nofSongsInSet; $iThSong++)
	{ 
		$songID = ubd_rp_get_song_id ($debug, $rID, $iThSet, $iThSong);
		if ($songID == -1)
		{
			up_error("$iThSong'th Song in set $iThSet (extra) not found");
		}
		else
		{
			if ($debug) up_info ("Set:$iThSet (extra) song:$iThSong: sID:$songID - adding");
			ubd_rp_add_repertoir_song($debug, $bandID, $NewrID, $iThSet, $songID, $comment);
		}
	}
	// COPY MANUSCRIPT
	$query = "SELECT * FROM bands.repertoir_talks WHERE repertoirID='$rID'";
    $result = mysql_query ($query);	
	
  	while ($row = mysql_fetch_array ($result))
	{
		ubd_rp_add_manuscript($debug, $bandID, $row[setNo], $row[afterIthSong], $NewrID); 
	}		
	return 	$NewrID;
}

function ubd_rp_get_song_id ($debug, $rID, $iThSet, $iThSong)
{
	$query = "SELECT * FROM bands.repertoir_songs WHERE repertoirID='$rID' AND setNo='$iThSet' AND orderNo='$iThSong'";
	
	if ($debug) up_info($query);
    $result = mysql_query ($query);	

  	while ($row = mysql_fetch_array ($result))
	{
		return $row[songID];
	}	
	return -1;
}
// ============================================
//	DELETE - REPERTOIR
// ============================================

function ubd_rp_delete($debug, $bandID, $repertoirID)
{
	if ($debug) up_error("ubd_rp_delete($debug, $bandID, $repertoirID): ENTER");
	
	if($bandID == "" || $repertoirID == "")
	{
		// You should not be able to erase another band's repertoir
		up_error("Invalid NULL parameter(s).");
		return;
	}

	$sql = "UPDATE bands.repertoir SET hidden=1 WHERE repertoirID='$repertoirID'";

    $result = mysql_query ($sql);	

	if ($debug) echo "sql:$sql ";
        
    if ($result == FALSE)
    {
		up_error("ERROR: ubd_rp_delete: Unable to update the post.");
		$msg = mysql_error();
		if ($msg)
			up_error($msg);
		return -1;
    } 	
	if ($debug) up_error("ubd_rp_delete: EXIT");
}	

function ubd_rp_print_delete_question($debug, $bandID, $rID, $bandMgmtPHP)
{
	if ($debug) up_note("ubd_rp_print_delete_question($debug, $bandID, $rID): ENTER");
	$name = ubd_rp_get_repertoir_name($debug, $bandID, $rID);
	echo "<h3>Are you sure you want to delete repertoir '$name'?</h3><br>";
	ub_check("$bandMgmtPHP?action=do_delete_repertoir&bandID=$bandID&rID=$rID", 20, "", "Yes" );
	ub_cancel("$bandMgmtPHP?action=view_repertoirs&bandID=$bandID", 20, "", "No" );
}	


function ubd_rp_print_add_pdf_form($debug, $bandID, $rID, $bandMgmtPHP)
{
//	up_error("Add pdf - Not yet implemented");
	if ($bandID == "" || $rID=="" || $bandMgmtPHP=="")
	{
		up_error("ubd_rp_print_add_pdf_form(bandID:$bandID, rID:$rID, php:$bandMgmtPHP): Invalid NULL parameters.");
		return;
	}
	if($debug) up_info("ubd_rp_print_add_pdf_form($debug, $bandID, $rID, $bandMgmtPHP)");
	up_form_header($bandMgmtPHP);
	// HIDDEN VARIABLES
	$UPLOAD_ACTION = "upload_repertoir_pdf";
	up_php_param("action", $UPLOAD_ACTION, "hidden");
	up_php_param("bandID", $bandID, "hidden");
	up_php_param("rID", $rID, "hidden");
	// TABLE
	up_table_header();
// up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)
	up_table_column("L�tlista <u>utan</u> texter:", 1, "right", "", "center");
// up_php_param($param, $value, $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked, $tableBgColor)
	up_php_param("short_pdf", "", "file", 1);
	up_new_table_row();
	up_table_column("L�tlista <u>med</u> texter:", 1, "right", "", "center");
// up_php_param($param, $value, $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked, $tableBgColor)
	up_php_param("long_pdf", "", "file", 1);
	up_new_table_row();

	up_table_column("", 1, "right", "", "center");
	echo "<td>";
	ub_save($URL, $height, $target, $title, $tableColumn, $valign, $columnWidth, $align, $bgcolor);
	up_form_footer();
	ub_cancel("$bandMgmtPHP?bandID=$bandID&action=view_repertoirs");
	echo "</td>";
	up_new_table_row();
	up_table_footer();

}

function ubd_rp_add_pdf($debug, $bandID, $name, $nofSets, $PHP_FILE_NAME )
{
	up_error("Do Add pdf - Not yet implemented");

}

function ubd_rp_update_pdf($debug, $bandID, $rID, $long_document, $short_document)
{
	// Update the Main Database
	if ($debug) up_info("ubd_rp_update_pdf($debug, $bandID, $rID, $long_document, $short_document)");
	if ($long_document == "" &&  $short_document == "")
	{
		up_error("ubd_rp_update_pdf: Invalid NULL parameters.");
		return;
	}
	$parameters = "repertoirID='$rID'";
	
	$dateNtime = date("Y-m-d_H:i:s");

	if ($long_document)
	{
		$parameters = $parameters . ", pdf='$long_document', longPDF_lastUpdate='$dateNtime'";
	}
 	if ($short_document)
	{
		$parameters = $parameters . ", short_pdf='$short_document', shortPDF_lastUpdate='$dateNtime'";
	}
    $sql = "UPDATE bands.repertoir SET $parameters WHERE repertoirID='$rID'";
	$result = mysql_query ($sql);
   	if($result != 1)
   	{
		up_error( "ubd_rp_update_pdf: Failed to update the database.");
		$msg = mysql_error();
		up_error( "mysql_error: $msg");
		up_error( "sql:  $sql");
	}
}
?>