<?php

function ubd_vi_calc_videos($debug, $bandID)
{
	if ($debug) up_note("ubd_vi_calc_videos($debug, $bandID): ENTER");
	$NofRehersals = 0;
	$today = date("Y-m-d");
	$sql = "SELECT COUNT(bandID) AS NofVideos FROM bands.videos WHERE bandID='$bandID'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array ($result);
   
    $NofVideos = $row[NofVideos];	
	if($debug) up_note( "ubd_vi_calc_videos: $NofRepertoirs = $sql");
	if ($debug) up_note("ubd_vi_calc_videos($debug, $bandID): RETURNING $NofVideos");
	return $NofVideos;
}
// ============================================
//	PRINT EDIT/ADD FORM - VIDEO
// ============================================


function ubd_vi_print_form($debug, $targetPHP, $bandID, $edit, $videoID)
{
	if ($debug) up_note("ubd_vi_print_form($debug, $targetPHP, $bandID, $edit, $videoID): ENTER");
// up_table_column($text, $bold, $align, $size, $valign, $open, $colspan, $underline, $bgcolor, $width, $fontsize)

	$tableColumn = 1; 
	up_table_header();
	up_form_header($targetPHP);
	up_php_param("action", "add_band_video", "hidden");
	up_php_param("bandID", $bandID, "hidden");

	$ralign = "right";
	// ============== 
	//	SPELNING
	// ==============
	up_table_column("Spelning:", 1, $ralign);
	ubd_gi_print_gigs_option_menu($debug, $bandID, $tableColumn);
	up_new_table_row();
	// ============== 
	//	LÅT
	// ==============
	up_table_column("Låttitel:", 1, $ralign);
	$size=40;
	up_php_param("title", "", $type, $tableColumn, $size);
	up_new_table_row();
	// ======= 
	//	URL
	// =======
	up_table_column("URL:", 1, $ralign);
	// up_php_param($param, $value, $type, $tableColumn, $size, $errorIndication, $readOnly, $textalign, $noBorder, $onChange, $colSpan, $tableAlign, $fontSizePx, $unit, $bgcolor, $maxlength, $tableValign, $radioChecked, $tableBgColor)
	$size=80;
	up_php_param("URL", $value, $type, $tableColumn, $size);
	up_new_table_row();
	$colspan =2;
	up_table_column("", 1, $ralign);
	up_table_column("Upload video to <a href=youtube.com target=newWin>YouTube.com</a> and paste the link above.", 1, $align, $fsize, $valign, $open, $colspan);
	up_new_table_row();
	up_table_column("", 1, $ralign);
	ub_save($URL, $height, $target, $title, $tableColumn);
	up_form_footer();
	up_table_footer();
	if ($debug) up_note("ubd_vi_print_form: EXIT");
}


// ============================================
//	VIEW - VIDEO
// ============================================

function  ubd_vi_get_id($debug, $URL)
{
	$posStart = strpos($URL, "v=");
	$posEnd = strlen($URL);
	if ($posStart === FALSE)
	{
		// Find Last slash (new style)
		if ($debug) up_note("$posStart === FALSE");
		$posStart = strrchr($URL, "/");
		$fileID = str_replace("/", "", $posStart);
	}
	else // v=<FILEid> (old style)
	{
		$posStart = $posStart + 2;
		$fileID = substr($URL, $posStart, ($posEnd-$posStart));
	}
	if ($debug) echo "ubd_vi_get_id($URL): '$fileID'";
	return $fileID;
}

function ubd_vi_view($debug, $bandID, $URL, $targetPHP, $fileID)
{
	// $debug = 1;
	if ($debug) up_note("ubd_vi_view($debug, $bandID, URL:'$URL', $targetPHP, fID:$fileID): ENTER");
	if ($fileID == "")
		$fileID = ubd_vi_get_id($debug, $URL);
	if($debug) up_note( "ID = $fileID");
	echo "<center>\n";
	if (strpos($fileID, "you") === FALSE)
		echo "<iframe width=560 height=315 src=\"//www.youtube.com/embed/$fileID\" frameborder=0 allowfullscreen></iframe>";
	else
		echo "<iframe width=560 height=315 src=\"$fileID\" frameborder=0 allowfullscreen></iframe>";

	ubd_vi_view_all($debug, $bandID, $targetPHP);	
	echo "</center>\n";
	
	if ($debug) up_note("ubd_vi_view: EXIT");
}

function 	ubd_vi_print_table_header($debug, $bandID)
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
	up_table_column("<br>Datum<br>", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("<br>Låt", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_table_column("<br>Plats", $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	up_new_table_row();
}

function ubd_vi_view_all($debug, $bandID, $targetPHP)
{
	if ($debug) up_note("ubd_vi_view_all($debug, $bandID): ENTER");

	$sql = "SELECT * FROM bands.videos WHERE bandID='$bandID' ORDER BY date DESC";

	up_table_header();
	ubd_vi_print_table_header($debug, $bandID);
	$underline = 1;
	$italic = 1;
 	$result = mysql_query ($sql);
	while ($row = mysql_fetch_array ($result))
	{	
		//$title = ubdc_macify($row[title]);
		//$location = ubdc_macify($row[location]);
		$title = $row[title];
		$location = $row[location];
		up_table_column($row[date], $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	//	up_table_column($title, $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
	// "$targetPHP?action=view_band_video&URL=$row[videoURL]&bandID=$bandID"
		ub_nice_link("$targetPHP?action=view_band_video&URL=$row[videoURL]&bandID=$bandID", "$title", "_self", "View video", 1, $onMouseOver, $onMouseOut, $fontSize,  $bold, !$italic, $valign, 0, 1, $openColumn, $bgcolor, "#3d5dde");

	//	echo "<a href=$targetPHP?action=view_band_video&URL=$row[videoURL]&bandID=$bandID>$title</a>";
//		up_nice_
		up_table_column($location, $bold, $align, $size, $valign, 1, $colspan, !$underline, $bgcolor);	
		up_new_table_row();	
	}
	up_table_footer();
	

	if ($debug) up_note("ubd_vi_view_all: EXIT");
}

// ============================================
//	ADD - VIDEO
// ============================================

function ubd_vi_add($debug, $bandID, $gigID, $title, $videoURL  )
{
	// $debug = 1;
	if ($debug) up_note("ubd_vi_add($debug, $bandID, $gigID, $title, $URL  ): ENTER");
 //  	$accomodationID = ubdc_get_unique_id($debug, "bands", "accomodation", "uID");

	if ($bandID > 0)
	{
		$variables = "bandID"; 
		$parameters = "'$bandID'"; 
	}	
	else
	{
		up_error("ubd_vi_add: Unique identifier invalid ($bandID).");
		return;
	}

	if ($gigID)
	{
		$variables = $variables . ", gigID"; 
		$parameters = $parameters . ", '$gigID'"; 
		ubd_gi_get_data($debug, $bandID,  $gigID, &$date, &$time, &$eventType, &$arenaID, &$repertoirID);
		if ($arenaID)
			ubdc_get_arena_data($debug, $dbase, $arenaID, &$name, &$streetAddress, &$city, &$postalCode, &$phoneNumber, &$URL);
	}
	if ($name)
	{
		$variables = $variables . ", location"; 
		$parameters = $parameters . ", '$name'"; 			
	}
	if ($date)
	{
		$variables = $variables . ", date"; 
		$parameters = $parameters . ", '$date'"; 	
	}
	if ($title)
	{
		$variables = $variables . ", title"; 
		$parameters = $parameters . ", '$title'"; 
	}
	if ($videoURL)
	{
		$variables = $variables . ", videoURL"; 
		$eventType = up_clean_var($bookingReference);
		$parameters = $parameters . ", '$videoURL'"; 
	}

    $sql = "INSERT INTO bands.videos ( $variables ) VALUES ( $parameters)";
    $result = mysql_query ($sql);	
  	if ($debug) up_note($sql);
      
    $returnValue = $hotelID;
    if ($result == FALSE)
    {
       	 up_error("ubd_vi_add: Unable to add the video in the database.");
       	 up_error("ubd_vi_add: $sql");
       	 $msg = mysql_error();
       	 if($msg)
          	 up_error("ubd_vi_add: $msg");
          $returnValue = 0;
    }	
    return  $returnValue;	
	if ($debug) up_note("ubd_vi_add: $sql EXIT");
}

// ============================================
//	UPDATE - VIDEO
// ============================================

function ubd_vi_update( $debug, $bandID, $videoID, $date, $time, $notes, $location )	
{
	if ($debug) up_note("ubd_vi_update: ENTER");

	if ($debug) up_note("ubd_vi_update: EXIT");
}

// ============================================
//	DELETE - VIDEO
// ============================================

function ubd_vi_delete($debug, $bandID, $videoID)
{
	if ($debug) up_note("ubd_vi_delete: ENTER");

	if ($debug) up_note("ubd_vi_delete: EXIT");
}	

?>